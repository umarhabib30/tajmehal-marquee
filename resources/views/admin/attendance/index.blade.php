@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>{{ $heading ?? 'Attendance' }}</h3>

        <div class="d-flex">
            <form id="monthForm" class="form-inline mr-2">
                <select name="month" class="form-control mr-1" onchange="this.form.submit()">
                    @for($m=1;$m<=12;$m++)
                        <option value="{{ $m }}" {{ $m==$month ? 'selected':'' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                    @endfor
                </select>
                <select name="year" class="form-control" onchange="this.form.submit()">
                    @for($y = date('Y')-2; $y <= date('Y')+1; $y++)
                        <option value="{{ $y }}" {{ $y==$year ? 'selected':'' }}>{{ $y }}</option>
                    @endfor
                </select>
            </form>

            <button id="saveAllBtn" class="btn btn-success">Save All</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered attendance-table" style="table-layout: fixed;">
            <thead>
                <tr>
                    <th style="min-width:220px">Staff</th>
                    @foreach($days as $day)
                        <th class="text-center" style="width:48px;">
                            <div>{{ $day->format('j') }}</div>
                            <div style="font-size:10px;">{{ $day->format('D') }}</div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($staffs as $staff)
                    <tr>
                        <td style="vertical-align: middle;">
                            <strong>{{ $staff->name ?? $staff->full_name ?? 'Staff '.$staff->id }}</strong>
                            <div class="text-muted small">ID: {{ $staff->id }}</div>
                        </td>

                        @foreach($days as $day)
                            @php
                                $key = $staff->id . '_' . $day->toDateString();
                                $rec = $records->get($key);
                                $status = $rec ? $rec->status : null;
                            @endphp

                            <td class="p-0 text-center attendance-cell"
                                data-staff="{{ $staff->id }}"
                                data-date="{{ $day->toDateString() }}"
                                data-status="{{ $status ?? '' }}"
                                style="cursor:pointer; height:56px;">
                                <div class="cell-content w-100 h-100 d-flex flex-column justify-content-center align-items-center">
                                    @if($status)
                                        <div class="status-badge status-{{ $status }}">{{ $status }}</div>
                                        <small class="time-text">
                                            @if($rec && $rec->entry_time) {{ \Carbon\Carbon::parse($rec->entry_time)->format('H:i') }} @endif
                                            @if($rec && $rec->exit_time) - {{ \Carbon\Carbon::parse($rec->exit_time)->format('H:i') }} @endif
                                        </small>
                                    @else
                                        <div class="text-muted">-</div>
                                    @endif
                                </div>
                            </td>
                        @endforeach

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Legend --}}
    <div class="mt-2">
        <span class="legend"><span class="dot present"></span> P (Present)</span>
        <span class="legend ml-3"><span class="dot absent"></span> A (Absent)</span>
        <span class="legend ml-3"><span class="dot leave"></span> L (Leave)</span>
        <span class="legend ml-3"><span class="dot off"></span> O (Off)</span>
    </div>
</div>

<!-- Edit modal for entry/exit/remarks (optional) -->
<div class="modal fade" id="editCellModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <form id="editCellForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Attendance</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" id="e_staff_id" name="staff_id">
            <input type="hidden" id="e_date" name="date">

            <div class="form-group">
                <label>Status</label>
                <select id="e_status" name="status" class="form-control">
                    <option value="">-- none --</option>
                    <option value="P">P (Present)</option>
                    <option value="A">A (Absent)</option>
                    <option value="L">L (Leave)</option>
                    <option value="O">O (Off)</option>
                </select>
            </div>

            <div class="form-group">
                <label>Entry Time</label>
                <input type="text" id="e_entry" name="entry_time" class="form-control" placeholder="09:00">
            </div>

            <div class="form-group">
                <label>Exit Time</label>
                <input type="text" id="e_exit" name="exit_time" class="form-control" placeholder="17:00">
            </div>

            <div class="form-group">
                <label>Remarks</label>
                <textarea id="e_remarks" name="remarks" class="form-control" rows="2"></textarea>
            </div>

            <div id="editError" class="alert alert-danger d-none"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button id="applyBtn" type="button" class="btn btn-primary">Apply to Cell</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('styles')
<style>
.attendance-table th, .attendance-table td { vertical-align: middle; padding:0; }
.cell-content { padding:6px; }
.status-badge { font-weight:700; color:#fff; padding:6px 6px; border-radius:4px; width:42px; text-align:center; }
.status-P { background:#28a745; } /* green */
.status-A { background:#dc3545; } /* red */
.status-L { background:#ffc107; color:#212529; } /* yellow */
.status-O { background:#6c757d; } /* gray */

.legend { font-weight:600; margin-right:12px; }
.dot { display:inline-block; width:14px; height:14px; margin-right:6px; border-radius:3px; vertical-align:middle; }
.dot.present { background:#28a745; }
.dot.absent { background:#dc3545; }
.dot.leave { background:#ffc107; }
.dot.off { background:#6c757d; }
</style>
@endsection

@section('scripts')
<script>
$(function(){
    // statuses cycle order
    const order = ['', 'P', 'A', 'L', 'O'];

    // track changed cells
    const changed = new Map();

    // click to cycle status
    $('.attendance-cell').on('click', function(e){
        var $cell = $(this);
        var cur = $cell.data('status') || '';
        var idx = order.indexOf(cur);
        idx = (idx + 1) % order.length;
        var next = order[idx];

        // update DOM & data-status
        $cell.data('status', next);
        if(next === ''){
            $cell.find('.cell-content').html('<div class="text-muted">-</div>');
        } else {
            var badge = '<div class="status-badge status-'+next+'">'+ next +'</div>';
            $cell.find('.cell-content').html(badge + '<small class="time-text"></small>');
        }

        // mark changed
        var key = $cell.data('staff') + '|' + $cell.data('date');
        changed.set(key, {
            staff_id: $cell.data('staff'),
            date: $cell.data('date'),
            status: next,
            entry_time: null,
            exit_time: null,
            remarks: null
        });

        // open edit modal on shift+click (optional)
        if(e.shiftKey){
            openEditModal($cell);
        }
    });

    // open edit modal (allows set times and remarks for one cell)
    function openEditModal($cell){
        $('#e_staff_id').val($cell.data('staff'));
        $('#e_date').val($cell.data('date'));
        var s = $cell.data('status') || '';
        $('#e_status').val(s);
        $('#e_entry').val('');
        $('#e_exit').val('');
        $('#e_remarks').val('');
        $('#editError').addClass('d-none').text('');
        $('#editCellModal').modal('show');

        // store ref to update after apply
        $('#editCellModal').data('targetCell', $cell);
    }

    // double click to open editor
    $('.attendance-cell').on('dblclick', function(){
        openEditModal($(this));
    });

    // apply changes from modal to that single cell
    $('#applyBtn').on('click', function(){
        var $cell = $('#editCellModal').data('targetCell');
        if(!$cell) return;

        var status = $('#e_status').val();
        var entry = $('#e_entry').val();
        var exit  = $('#e_exit').val();
        var remarks = $('#e_remarks').val();

        // update data-status and small text
        $cell.data('status', status);

        if(status === ''){
            $cell.find('.cell-content').html('<div class="text-muted">-</div>');
        } else {
            var badge = '<div class="status-badge status-'+status+'">'+ status +'</div>';
            var small = '<small class="time-text">';
            if(entry) small += entry;
            if(exit) small += (entry ? ' - ' : '') + exit;
            small += '</small>';
            $cell.find('.cell-content').html(badge + small);
        }

        // mark changed
        var key = $cell.data('staff') + '|' + $cell.data('date');
        changed.set(key, {
            staff_id: $cell.data('staff'),
            date: $cell.data('date'),
            status: status,
            entry_time: entry || null,
            exit_time: exit || null,
            remarks: remarks || null
        });

        $('#editCellModal').modal('hide');
    });

    // Save All button - gather changed and send
    $('#saveAllBtn').on('click', function(){
        if(changed.size === 0){
            alert('No changes to save.');
            return;
        }

        var entries = [];
        changed.forEach(function(v,k){
            entries.push(v);
        });

        $.ajax({
            url: '{{ route("admin.attendance.bulkSave") }}',
            method: 'POST',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: { entries: entries },
            success: function(res){
                if(res.success){
                    alert(res.message);
                    changed.clear();
                    // optionally reload to ensure DB sync
                    location.reload();
                } else {
                    alert('Failed to save.');
                }
            },
            error: function(xhr){
                var msg = 'Error saving.';
                if(xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON.message;
                alert(msg);
            }
        });
    });

});

</script>
@endsection
