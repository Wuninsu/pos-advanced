<?php

namespace App\Livewire\Admin;

use App\Models\SmsLog;
use Exception;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class SmsLogs extends Component
{
    use WithPagination;
    public $smsLogs = [];



    public $inputData = [];
    public $reTryStatus = [];

    public function mount()
    {
        // load sms status
        $this->loadStatus();
    }

    public function updated()
    {
        $this->loadStatus();
    }

    private function loadStatus()
    {
        $failed = SmsLog::getFailedSms();
        $sent = SmsLog::getSentSms();
        $this->reTryStatus['failed'] = count($failed) ?? 0;
        $this->reTryStatus['success'] = count($sent) ?? 0;
    }


    public function examResultsSummary()
    {
        $validData = $this->validate();
        $semester = $validData['selectedSemester'];
        $class = $validData['selectedClass'];
        $filters = [
            'semesterId' => $semester,
            'classId' => $class
        ];
        $results = [];

        if (!$results) {
            flash()->warning('No data was found');
            return;
        }

        $this->sendSmsAndLog($results, 'exam_results_summary', function ($record) {
            return [
                'parent_name' => $record['parent_name'],
                'academic_year' => $record['academic_year'],
                'total_marks' => $record['total_marks'],
                'average_marks' => $record['average_marks'],
                'lowest_marks' => $record['lowest_marks'],
                'highest_marks' => $record['highest_marks'],
                'student_name' => $record['student_name'],
                'position' => $record['position'],
            ];
        });

        return true;
    }


    private function sendSmsAndLog($results, $messageType, $placeholdersCallback)
    {
        $successCount = 0;
        $failureCount = 0;

        foreach ($results as $record) {
            // Dynamically generate placeholders using the callback
            $placeholders = $placeholdersCallback($record);
            $smsMessage = generateSmsMessage($messageType, $placeholders);
            try {
                // Attempt to send the SMS
                $status = SmsLog::sendSMS($record['phone'], $smsMessage) ? 'sent' : 'failed';

                $this->smsLogs[] = "Sending SMS to {$record['customer']} ({$record['phone']}): {$status}";
                if ($status === 'sent') {
                    $successCount++;
                } else {
                    $failureCount++;
                }
            } catch (Exception $e) {
                // Handle SMS sending failure
                $status = 'failed';
                $this->smsLogs[] = "Failed to send SMS to {$record['customer']} ({$record['phone']}): {$e->getMessage()}";
            }

            // Log the result to the database
            SmsLog::updateOrCreate(
                [
                    'recipient_phone' => $record['phone'],
                    'message' => $smsMessage,
                ]
            );
        }

        // Provide feedback to the user
        if ($successCount > 0 && $failureCount === 0) {
            flash()->success("$successCount SMS sent successfully.");
        } elseif ($successCount > 0 && $failureCount > 0) {
            flash()->warning("$successCount SMS sent successfully, but $failureCount failed.");
        } elseif ($successCount === 0 && $failureCount > 0) {
            flash()->error("All SMS failed to send. Please check your SMS gateway or internet access.");
        } else {
            flash()->info("No SMS were sent.");
        }
        // Push the final log to the $smsLogs property
        $this->smsLogs[] = 'SMS sending process completed.';
    }



    public function retryFailedSms()
    {
        $results = SmsLog::retryFailedSms();
        $this->reTryStatus = $results;
    }

    public function deleteAllSent()
    {
        $results = SmsLog::deleteAllSent();
        $this->smsLogs;
    }

    public function getSavedSmsLogsProperty()
    {
        return SmsLog::latest()
            ->paginate(paginationLimit());
    }



    #[Title('SMS Logs')]
    public function render()
    {
        return view('livewire.admin.sms-logs', ['savedSmsLogs' => $this->savedSmsLogs]);
    }
}
