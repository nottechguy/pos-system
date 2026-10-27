@extends('layouts.dashboard')

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Gestión de Usuarios y Cajeros</h2>
        <p>Administre los operadores, cajeros y permisos del sistema.</p>
    </div>
    <div class="dashboard-header__actions">
        <x-bmdc-button type="link" href="{{ route('admin.users.create') }}" variant="raised">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#ffffff"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            </x-slot>
            Nuevo Usuario
        </x-bmdc-button>
    </div>
</div>

<div class="form-panel">
    <table class="table" style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="text-align: left; border-bottom: 1px solid #e5e7eb;">
                <th style="padding: 10px;">Avatar</th>
                <th style="padding: 10px;">Código</th>
                <th style="padding: 10px;">Nombre Completo</th>
                <th style="padding: 10px;">Email / Teléfono</th>
                <th style="padding: 10px;">Rol</th>
                <th style="padding: 10px;">Estado</th>
                <th style="padding: 10px; text-align: right;">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr style="border-bottom: 1px solid #f3f4f6;">
                <td style="padding: 10px;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; overflow: hidden; background: #e5e7eb; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                        @if($user->avatar_path)
                            <img src="{{ Storage::url($user->avatar_path) }}" alt="" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            {{ strtoupper(substr($user->first_name, 0, 1)) }}
                        @endif
                    </div>
                </td>
                <td style="padding: 10px;">{{ $user->code }}</td>
                <td style="padding: 10px; font-weight: 500;">{{ $user->full_name }}</td>
                <td style="padding: 10px; color: #6b7280; font-size: 0.9rem;">{{ $user->email }}<br>{{ $user->phone }}</td>
                <td style="padding: 10px;"><span style="background: #e0e7ff; color: #3730a3; padding: 2px 8px; border-radius: 4px; font-size: 0.85rem;">{{ $user->role->name ?? 'N/A' }}</span></td>
                <td style="padding: 10px;">
                    <span style="color: {{ $user->status === 'active' ? '#059669' : '#dc2626' }}; font-weight: 500;">
                        {{ ucfirst($user->status) }}
                    </span>
                </td>
                <td style="padding: 10px; text-align: right;">
                    <a href="{{ route('admin.users.edit', $user->id) }}" style="margin-right: 10px; color: #2563eb; text-decoration: none;">Editar</a>
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Está seguro de eliminar este usuario?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="background: none; border: none; color: #dc2626; cursor: pointer; padding: 0;">Eliminar</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 2rem; color: #6b7280;">No hay usuarios registrados.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1.5rem;">
        {{ $users->links() }}
    </div>
</div>
@endsection