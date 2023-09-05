@if (!empty($prescription) )
<!-- Modal -->
<div class="modal fade" id="exampleModal{{ $prescription->booking_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('prescription.store') }}" method="post" enctype="multipart/form-data">@csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Upload Reports</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>   
                </div>
                <div class="modal-body" id="app">
                    <input type="hidden" name="user_id" value="{{ $prescription->user_id }}">
                    <input type="hidden" name="doctor_id" value="{{ $prescription->doctor_id }}">
                    <input type="hidden" name="date" value="{{ $prescription->date }}">
                    <input type="hidden" name="booking_id" value="{{ $prescription->booking_id }}">
                    <input type="hidden" name="prescription_id" value="{{ $prescription->id }}">
                    <div class="form-group">
                        <label>Reports </label>
                        <input type="file" name="attachements[]" accept="*" class="form-control" required multiple>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif