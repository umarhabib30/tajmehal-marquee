@extends('layouts.admin')

@section('style')
    <style>
        .inventory-history-page .card {
            border-top: 3px solid #352a86;
            border-radius: 10px;
            box-shadow: 0 8px 22px rgba(26, 20, 77, 0.08);
            overflow: hidden;
        }

        .inventory-history-page .card-header {
            background: linear-gradient(120deg, #332881 0%, #4b3eb6 100%);
            color: #fff;
            border-radius: 10px 10px 0 0 !important;
            border-bottom: 0;
            padding: 14px 16px;
        }

        .history-title {
            color: #fff;
            font-weight: 700;
            margin: 0;
            font-size: 1.1rem;
        }
    </style>
@endsection

@section('content')
    <div class="row inventory-history-page">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                    <h5 class="history-title mb-2">Stock Reset Backup History</h5>
                    <a href="{{ route('inventory.index') }}" class="btn btn-light btn-sm mb-2">
                        <i class="fa fa-arrow-left"></i> Back to Inventory
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead>
                                <tr>
                                    <th>Batch ID</th>
                                    <th>Deleted Records</th>
                                    <th>Deleted At</th>
                                    <th>Deleted By</th>
                                    <th>Status</th>
                                    <th>Restored At</th>
                                    <th>Restored By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($resetHistory as $batch)
                                    <tr>
                                        <td>{{ $batch->id }}</td>
                                        <td>{{ number_format($batch->total_records) }}</td>
                                        <td>{{ $batch->deleted_at }}</td>
                                        <td>{{ $batch->deleted_by_name ?? 'N/A' }}</td>
                                        <td>
                                            @if ($batch->restored_at)
                                                <span class="badge badge-success">Restored</span>
                                            @else
                                                <span class="badge badge-warning">Backup Available</span>
                                            @endif
                                        </td>
                                        <td>{{ $batch->restored_at ?? '—' }}</td>
                                        <td>{{ $batch->restored_by_name ?? '—' }}</td>
                                        <td>
                                            @if (!$batch->restored_at)
                                                <form id="restore-form-{{ $batch->id }}"
                                                    action="{{ route('inventory.stock.restore', $batch->id) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    <button type="button" class="btn btn-outline-primary btn-sm restore-btn"
                                                        data-id="{{ $batch->id }}">
                                                        Restore
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted">Done</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No reset backup history found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if (method_exists($resetHistory, 'links'))
                        <div class="mt-3">
                            {{ $resetHistory->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const restoreButtons = document.querySelectorAll('.restore-btn');
            restoreButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const batchId = this.getAttribute('data-id');
                    Swal.fire({
                        title: 'Restore this backup batch?',
                        text: 'This will re-insert all stock records from this batch and recalculate inventory quantities.',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, restore batch',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('restore-form-' + batchId).submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
