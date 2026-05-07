@php
    $perms = old('permissions', $permissions ?? []);
    $moduleList = $modules ?? config('admin_modules.modules') ?? [];
    $actionList = $actions ?? config('admin_modules.actions') ?? [];
@endphp
<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Module</th>
                @foreach ($actionList as $actionKey => $actionLabel)
                    <th class="text-center">{{ $actionLabel }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($moduleList as $moduleKey => $moduleLabel)
                <tr>
                    <td>{{ $moduleLabel }}</td>
                    @foreach ($actionList as $actionKey => $actionLabel)
                        @php
                            $checked = !empty($perms[$moduleKey][$actionKey]);
                        @endphp
                        <td class="text-center">
                            <input type="checkbox" name="permissions[{{ $moduleKey }}][{{ $actionKey }}]" value="1"
                                {{ $checked ? 'checked' : '' }}>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<p class="text-muted small mb-0">Staff users need <strong>View</strong> on at least one module to sign in. Dashboard view is required to open the home dashboard.</p>
