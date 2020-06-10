<div class="mx-2 btn-group">
@auth
    <!-- Acknowledgement button -->
        @if($report->reporter_id === auth()->id() && $report->decision === 0 && is_null($report->acked_at))
            <a class="btn btn-warning"
               href="{{ route('my-reports.ack', $report) }}"
            >Ack</a>
        @endif

    <!-- Add video button -->
        @if(auth()->user()->admin)
            <a class="btn btn-outline-primary{{ $report->decided ? ' disabled' : '' }}"
               href="#"
               data-toggle="modal"
               data-target="#report-attach-video-{{ $report->id }}"
            >
                <i class="fa fa-plus" aria-hidden="true"></i>
                <i class="fa fa-video-camera" aria-hidden="true"></i>
            </a>
        @endif

    <!-- Download button -->
        <a class="btn btn-primary"
           title="{{ $report->demoFilename }}"
           href="{{ $report->demoUrl }}"
        >
            <i class="fa fa-download" aria-hidden="true"></i>
            Demo
        </a>

        <!-- Decide as correct button -->
        <a class="btn btn-outline-success{{ $report->decided ? ' disabled' : '' }}"
           href="#"
           data-toggle="modal"
           data-target="#report-decision-correct-{{ $report->id }}"
        >
            <i class="fa fa-thumbs-up" aria-hidden="true"></i>
            Correct
        </a>

        <!-- Decide as incorrect button -->
        <a class="btn btn-outline-danger{{ $report->decided ? ' disabled' : '' }}"
           href="#"
           data-toggle="modal"
           data-target="#report-decision-incorrect-{{ $report->id }}"
        >
            <i class="fa fa-thumbs-down" aria-hidden="true"></i>
            Incorrect
        </a>

        <!-- Ignore report button -->
        <a class="btn btn-outline-warning{{ $report->decided ? ' disabled' : '' }}"
           href="#"
           data-toggle="modal"
           data-target="#report-ignore-{{ $report->id }}"
        ><i class="fa fa-ban" aria-hidden="true"></i></a>

        <!-- Delete report -->
        <a class="btn btn-outline-danger{{ $report->decided ? ' disabled' : '' }}"
           href="#"
           data-toggle="modal"
           data-target="#report-delete-{{ $report->id }}"
        ><i class="fa fa-trash" aria-hidden="true"></i></a>
    @endauth
</div>
