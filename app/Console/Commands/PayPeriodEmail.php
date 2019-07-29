<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PayPeriodEmail extends Command
{
  public function queue_users()
  {
    $users = User::all();
    
    foreach ($users as $user) {
      $data = [
        'name' => $user['name'],
        'email' => $user['email'],
        'subject' => 'Reminder - 2 Week Pay Period Due',
      ];
    }
    Queue::push(new PayPeriodEmail($data));
  }
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'command:payperiodemail';

  /**
   * The console command description.
   *
   * @var string
   */
  protected $description = 'Command description';

  /**
   * Create a new command instance.
   *
   * @return void
   */
  protected $data;

  public function __construct($data)
  {
    $this->data = $data;
  }

  /**
   * Execute the console command.
   *
   * @return mixed
   */
  public function handle()
  {
    $data = $this->data;

    Mail::send('emails.reminder_html', $data, function($message) use ($data)
    {
      $message->to($data['email'], $data['name']);
      $message->subject($data['subject']);
    });
  }
}
