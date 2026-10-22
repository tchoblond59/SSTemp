<?php

namespace PluginTests;

use App\Message;
use App\Sensor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Tchoblond59\SSTemp\Models\SSTemp;
use Tchoblond59\SSTemp\Models\SSTempConfig;
use Tchoblond59\SSTemp\Models\SSTempEmail;

class PluginTest extends TestCase
{
    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function testAnonymousConfigurationIsProtected(): void
    {
        $this->get('/widget/SSTemp/1')->assertRedirect('/login');
    }

    public function testFirstConfigurationPageCreatesSettingsWithoutHistory(): void
    {
        $sensor = $this->sensor(SSTemp::class);
        $this->widget($sensor);
        $this->get('/widget/SSTemp/'.$sensor->id)->assertStatus(200);
        $this->assertDatabaseHas('sstemp_configs', ['sensor_id' => $sensor->id]);
    }

    public function testTemperatureReadingsAndStatsIgnoreHumidity(): void
    {
        $sensor = $this->sensor(SSTemp::class);
        $this->message(0, '20');
        $this->message(0, '24');
        $this->message(1, '80');
        $this->message(0, '99', 1, 2);
        $temperature = SSTemp::findOrFail($sensor->id);
        $this->assertSame('24', $temperature->getLastTemperature());
        $stats = $temperature->getTempStats();
        $this->assertCount(1, $stats);
        $this->assertEquals(22, $stats->first()->temp_avg);
    }

    public function testHumidityDoesNotTriggerAlertAndTemperatureEventDoes(): void
    {
        \Illuminate\Support\Facades\Event::fake([\Tchoblond59\SSTemp\Events\SSTempEvent::class]);
        $sensor = $this->sensor(SSTemp::class);
        $settings = new SSTempConfig();
        $settings->forceFill(['sensor_id' => $sensor->id, 'limit' => 30]);
        $settings->save();
        $email = new SSTempEmail();
        $email->forceFill(['sstemp_config_id' => $settings->id, 'email' => 'alerts@example.test']);
        $email->save();
        config(['mail.default' => 'array', 'mail.mailers.array' => ['transport' => 'array'], 'mail.from.address' => 'larahome@example.test']);
        $this->message(0, '20');
        $humidity = $this->message(1, '90');
        $listener = new \Tchoblond59\SSTemp\EventListener\SSTempEventListener();
        $listener->handle(new \App\Events\MSMessageEvent(new \App\Mqtt\MSMessage($humidity->id)));
        $this->assertCount(0, Mail::mailer()->getSymfonyTransport()->messages());
        $temperature = $this->message(0, '35');
        $listener->handle(new \App\Events\MSMessageEvent(new \App\Mqtt\MSMessage($temperature->id)));
        $this->assertCount(1, Mail::mailer()->getSymfonyTransport()->messages());
        \Illuminate\Support\Facades\Event::assertDispatchedTimes(\Tchoblond59\SSTemp\Events\SSTempEvent::class, 2);
    }

    public function testThresholdCanBeSavedWithoutEmailBeforeOpeningSettings(): void
    {
        $sensor = $this->sensor(SSTemp::class);
        $this->widget($sensor);
        $this->post('/update/sstemp/'.$sensor->id, ['temp_lim' => 30, 'mail' => ''])->assertStatus(302);
        $this->assertDatabaseHas('sstemp_configs', ['sensor_id' => $sensor->id, 'limit' => 30]);
    }

    public function testTemperatureAlertsKeepThirtyMinuteCooldownAndRenderMail()
    {
        Carbon::setTestNow(Carbon::parse('2026-10-22 12:00:00'));
        $sensor = Sensor::create(['node_address' => 12, 'sensor_address' => 3, 'name' => 'Test temperature', 'classname' => SSTemp::class]);
        $message = new Message();
        $message->forceFill(['node_address' => 12, 'sensor_address' => 3, 'command' => 1, 'ack' => 0, 'type' => 0, 'value' => '35']);
        $message->save();
        $settings = new SSTempConfig();
        $settings->forceFill(['sensor_id' => $sensor->id, 'limit' => 30, 'last_send' => Carbon::now()->subMinutes(31)]);
        $settings->save();
        $email = new SSTempEmail();
        $email->forceFill(['sstemp_config_id' => $settings->id, 'email' => 'alerts@example.test']);
        $email->save();
        config(['mail.default' => 'array', 'mail.mailers.array' => ['transport' => 'array'], 'mail.from.address' => 'larahome@example.test']);
        $this->assertInstanceOf(Carbon::class, $settings->fresh()->last_send);
        $settings->fresh()->sendAlert();
        $messages = Mail::mailer()->getSymfonyTransport()->messages();
        $this->assertCount(1, $messages);
        $mail = $messages->first()->getOriginalMessage();
        $this->assertSame('alerts@example.test', $mail->getTo()[0]->getAddress());
        $this->assertSame('Alerte sonde température!', $mail->getSubject());
        $this->assertStringContainsString('35°C', $mail->getHtmlBody());
        $settings->fresh()->sendAlert();
        $this->assertCount(1, Mail::mailer()->getSymfonyTransport()->messages());
        Carbon::setTestNow(Carbon::now()->addMinutes(30));
        $settings->fresh()->sendAlert();
        $this->assertCount(2, Mail::mailer()->getSymfonyTransport()->messages());
    }

}
