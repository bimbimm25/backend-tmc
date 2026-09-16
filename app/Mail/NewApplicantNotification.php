<?php

namespace App\Mail;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class NewApplicantNotification extends Mailable
{
    use Queueable, SerializesModels;

    public JobApplication $application;

    public function __construct(JobApplication $application)
    {
        $this->application = $application;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Lamaran Baru: ' . $this->application->full_name . ' - ' . $this->application->career->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-applicant',
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if ($this->application->resume_path && Storage::disk('public')->exists($this->application->resume_path)) {
            $attachments[] = Attachment::fromPath(Storage::disk('public')->path($this->application->resume_path));
        }

        return $attachments;
    }
}