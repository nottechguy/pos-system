@extends('layouts.dashboard')

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>Editar Usuario: {{ $user->full_name }}</h2>
        <p>Modifique los datos del operador o cajero.</p>
    </div>
</div>

<div class="form-panel" data-jscontroller="BusinessUIController">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="first_name">Nombre *</label>
                    <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name', $user->first_name) }}" required autocomplete="off">
                    @error('first_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="last_name">Apellido *</label>
                    <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name', $user->last_name) }}" required autocomplete="off">
                    @error('last_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="code">Código de Empleado (max 6 chars) *</label>
                    <input type="text" name="code" id="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $user->code) }}" maxlength="6" required autocomplete="off">
                    @error('code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="pin">PIN de Acceso Rápido (4 dígitos)</label>
                    <input type="password" name="pin" id="pin" class="form-control @error('pin') is-invalid @enderror" maxlength="4" autocomplete="off" placeholder="Dejar en blanco para mantener">
                    @error('pin') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" autocomplete="off">
                    @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="phone">Teléfono</label>
                    <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}" autocomplete="off">
                    @error('phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="role_id">Rol de Usuario *</label>
                    <select name="role_id" id="role_id" class="form-control @error('role_id') is-invalid @enderror" required>
                        <option value="">-- Seleccione Rol --</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                        @endforeach
                    </select>
                    @error('role_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="status">Estado *</label>
                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                        <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="password">Contraseña (Dejar en blanco para no modificar)</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                    @error('password') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label>Tiendas Asignadas</label>
                    <div style="max-height: 120px; overflow-y: auto; border: 1px solid #d1d5db; padding: 8px; border-radius: 6px;">
                        @php $assignedStores = old('stores', $user->stores->pluck('id')->toArray()); @endphp
                        @foreach($stores as $store)
                            <label style="display: block; margin-bottom: 4px;">
                                <input type="checkbox" name="stores[]" value="{{ $store->id }}" {{ in_array($store->id, $assignedStores) ? 'checked' : '' }}>
                                {{ $store->name }}
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="business_image_file_input">Avatar / Fotografía</label>
            <div class="business-dialog-cover-img__container">
                <div class="business-dialog-cover-img__container--fileuploader">
                    <div id="business_dialog_image_preview_container" @class(["business-dialog-cover-img", "business-dialog-cover-img__preview", "content-show" => $user->avatar_path !== NULL])>
                        <div class="business-dialog-cover-img__preview__gradient"></div>
                        <img src="{{ $user->avatar_path ? Storage::url($user->avatar_path) : '#' }}" id="logo_preview_img" alt="">
                    </div>
                    <div id="business_dialog_image_file_container" @class(["business-dialog-cover-img", "business-dialog-cover-img__input", "content-hide" => $user->avatar_path !== NULL])>
                        <div class="business-dialog-cover-img__input--text">
                            <span>Subir Avatar del Usuario</span>
                        </div>
                        <input id="business_image_file_input" class="business-dialog-cover-img__inputfile" type="file" name="logo_path" accept="image/*" data-jscontroller="BusinessUIController" data-jschange="@showBannerImagePreview" data-jsparams="{'image_preview': 'logo_preview_img'}">
                    </div>
                </div>
            </div>
            @error('logo_path') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <div class="form-group form-group-send">
            <x-bmdc-button type="submit" variant="raised">Actualizar Usuario</x-bmdc-button>
        </div>
    </form>
</div>
@endsection