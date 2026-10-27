@extends('layouts.dashboard')

@section('content')
<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>{{ __('messages.dashboard.business.headline') }}</h2>
        <p>{{ __('messages.dashboard.business.description') }}</p>
    </div>
</div>

<div class="form-panel" data-jscontroller="BusinessUIController">
    <form action="{{ route('admin.business.update', $business->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="name">{{ __('messages.dashboard.business.form.business_name') }} *</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $business->name) }}" required autocomplete="off">
                    @error('name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="legal_name">{{ __('messages.dashboard.business.form.legal_name') }}</label>
                    <input type="text" name="legal_name" id="legal_name" class="form-control @error('legal_name') is-invalid @enderror" value="{{ old('legal_name', $business->legal_name) }}" autocomplete="off">
                    @error('legal_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="tax_id">{{ __('messages.dashboard.business.form.rfc') }} *</label>
                    <input type="text" name="tax_id" id="tax_id" class="form-control @error('tax_id') is-invalid @enderror" value="{{ old('tax_id', $business->tax_id) }}" required autocomplete="off">
                    @error('tax_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="category_id">{{ __('messages.dashboard.business.form.business_category') }}</label>
                    <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror">
                        <option value="">-- {{ __('messages.dashboard.business.form.select_category') }} --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $business->category_id) == $category->id ? 'selected' : '' }}>
                                {{ __("messages.business_categories.{$category->code}") }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="bio">{{ __('messages.dashboard.business.form.description') }}</label>
            <textarea name="bio" id="bio" class="form-control @error('bio') is-invalid @enderror">{{ old('bio', $business->bio) }}</textarea>
            @error('bio') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="phone_line_1">{{ __('messages.dashboard.business.form.phone_number_1') }}</label>
                    <input type="text" name="phone_line_1" id="phone_line_1" class="form-control @error('phone_line_1') is-invalid @enderror" value="{{ old('phone_line_1', $business->phone_line_1) }}" autocomplete="off"> 
                    @error('phone_line_1') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="phone_line_2">{{ __('messages.dashboard.business.form.phone_number_2') }}</label>
                    <input type="text" name="phone_line_2" id="phone_line_2" class="form-control @error('phone_line_2') is-invalid @enderror" value="{{ old('phone_line_2', $business->phone_line_2) }}" autocomplete="off">
                    @error('phone_line_2') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="email">{{ __('messages.dashboard.business.form.email') }}</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $business->email) }}" autocomplete="off">
                    @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="website">{{ __('messages.dashboard.business.form.website') }}</label>
                    <input type="url" name="website" id="website" class="form-control @error('website') is-invalid @enderror" value="{{ old('website', $business->website) }}" autocomplete="off">
                    @error('website') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="currency_code">{{ __('messages.dashboard.business.form.default_currency') }}</label>
                    <select name="currency_code" id="currency_code" class="form-control @error('currency_code') is-invalid @enderror">
                        <option value="MXN" {{ old('currency_code', $business->currency_code) == 'MXN' ? 'selected' : '' }}>MXN - Peso Mexicano</option>
                        <option value="USD" {{ old('currency_code', $business->currency_code) == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                    </select>
                    @error('currency_code') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="form-col">
                <div class="form-group">
                    <label for="timezone">{{ __('messages.dashboard.business.form.timezone') }}</label>
                    <select name="timezone" id="timezone" class="form-control @error('timezone') is-invalid @enderror">
                        <option value="America/Mexico_City" {{ old('timezone', $business->timezone) == 'America/Mexico_City' ? 'selected' : '' }}>America/Mexico_City</option>
                        <option value="America/Tijuana" {{ old('timezone', $business->timezone) == 'America/Tijuana' ? 'selected' : '' }}>America/Tijuana</option>
                        <!-- Add additional timezones as needed -->
                    </select>
                    @error('timezone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-group">


            <label for="business_image_file_input">{{ __('messages.dashboard.business.form.logo') }}</label>

            <div class="business-dialog-cover-img__container">
                <div class="business-dialog-cover-img__container--fileuploader">
                    <div id="business_dialog_image_preview_container" @class(["business-dialog-cover-img", "business-dialog-cover-img__preview", "content-show" => $business->logo_path !== NULL]) >
                        <div class="business-dialog-cover-img__preview__gradient"></div>
                        <img src="{{ $business->logo_path ? Storage::url($business->logo_path) : '#' }}" id="logo_preview_img" alt="">
                        <button type="button" data-jscontroller="BusinessUIController" data-jsclick="@clearBannerImagePreview" data-jsparams="{'image_preview': 'logo_preview_img', 'input': 'logo_path'}">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m336-280 144-144 144 144 56-56-144-144 144-144-56-56-144 144-144-144-56 56 144 144-144 144 56 56ZM480-80q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>
                        </button>
                    </div>
                    <div id="business_dialog_image_file_container" @class(["business-dialog-cover-img", "business-dialog-cover-img__input", "content-hide" => $business->logo_path !== NULL])>
                        <div class="business-dialog-cover-img__input--text">
                            <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#000000"><path d="M480-480ZM200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280q17 0 28.5 11.5T520-800q0 17-11.5 28.5T480-760H200v560h560v-280q0-17 11.5-28.5T800-520q17 0 28.5 11.5T840-480v280q0 33-23.5 56.5T760-120H200Zm40-160h480L570-480 450-320l-90-120-120 160Zm440-400h-40q-17 0-28.5-11.5T600-720q0-17 11.5-28.5T640-760h40v-40q0-17 11.5-28.5T720-840q17 0 28.5 11.5T760-800v40h40q17 0 28.5 11.5T840-720q0 17-11.5 28.5T800-680h-40v40q0 17-11.5 28.5T720-600q-17 0-28.5-11.5T680-640v-40Z"/></svg>
                            <span>{{ __('messages.dashboard.business.form.logo_label') }}</span>
                        </div>
                        <input id="business_image_file_input" class="business-dialog-cover-img__inputfile" type="file" name="logo_path" accept="image/*" data-jscontroller="BusinessUIController" data-jschange="@showBannerImagePreview" data-jsparams="{'image_preview': 'logo_preview_img'}">
                    </div>
                </div>
            </div>

            @error('logo_path') <span class="invalid-feedback">{{ $message }}</span> @enderror

            <!--<div class="logo-preview-box">
                <img id="logo_preview_img" src="{{ $business->logo_path ? Storage::url($business->logo_path) : '' }}" style="{{ $business->logo_path ? 'display:block;' : 'display:none;' }}" alt="Logo Preview">
            </div>


            <input type="file" name="logo_path" id="logo_path" class="form-control @error('logo_path') is-invalid @enderror" accept="image/*" data-jschange="@previewLogo">
            @error('logo_path') <span class="invalid-feedback">{{ $message }}</span> @enderror-->

        </div>

        <div class="form-group form-group-send">
            <x-bmdc-button type="submit" variant="raised">{{ __('messages.dashboard.business.form.save') }}</x-bmdc-button>
        </div>
    </form>
</div>
@endsection