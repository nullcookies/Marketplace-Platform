<?php
namespace App\Enum;

enum QueueJobStatus: string
{
    case Queued = 'queued';
    case Processing = 'processing';
    case Published = 'published';
    case Failed = 'failed';
}
