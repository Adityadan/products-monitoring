<x-templates.default>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-sm-auto mb-2 mb-sm-0">
                    <h5 class="mb-0">Assign Roles to Users</h5>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered font-sans-serif" id="roles-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>User</th>
                                @foreach ($roles as $role)
                                    <th>{{ ucwords(str_replace('_', ' ', $role->name)) }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    @foreach ($roles as $role)
                                        <td class="text-center">
                                            <input type="checkbox" class="role-checkbox"
                                                data-user-id="{{ $user->id }}" data-role-name="{{ $role->name }}"
                                                {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Handle checkbox state changes
                $('.role-checkbox').on('change', function() {
                    const userId = $(this).data('user-id');
                    const roleName = $(this).data('role-name');
                    const isChecked = $(this).is(':checked');

                    const url = isChecked ?
                        '{{ route('roles.assign.store') }}' :
                        '{{ route('roles.remove') }}';

                    const data = {
                        _token: '{{ csrf_token() }}',
                        user_id: userId,
                        role: roleName,
                    };

                    $.ajax({
                        url: url,
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                            });
                        },
                        error: function(xhr) {
                            let errorMessage = 'An error occurred.';
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                errorMessage = Object.values(xhr.responseJSON.errors).join(', ');
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errorMessage,
                            });
                        },
                    });
                });
            });
        </script>
    @endpush
</x-templates.default>
