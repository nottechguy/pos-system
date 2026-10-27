@extends('layouts.dashboard')

@section('content')

<div class="dashboard-header">
    <div class="dashboard-header__meta">
        <h2>{{ __('messages.dashboard.products.pricing.headline') }}: {{ $product->name }}</h2>
        <p style="color: var(--text-muted);">SKU: {{ $product->sku }} | {{ ucwords(__("messages.measurement_units.{$product->saleUnit->code}.name")) }}</p>
    </div>
    <div class="dashboard-header__actions" style="display: flex; gap: 10px;">
        <x-bmdc-button type="link" href="{{ route('admin.products.edit', $product->id) }}" variant="outlined">
            <x-slot name="mIcon">
                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="m313-440 224 224-57 56-320-320 320-320 57 56-224 224h487v80H313Z"/></svg>
            </x-slot>
            {{ __('messages.dashboard.products.back') }}
        </x-bmdc-button>
    </div>
</div>

<!-- BASE PRICES PANEL -->
<div class="form-panel">
    <h3 style="margin-top: 0; margin-bottom: 20px; color: var(--primary-color);">{{ __('messages.dashboard.products.pricing.form.base_prices') }}</h3>
    <form action="{{ route('admin.products.pricing.store', $product->id) }}" method="POST">
        @csrf

        <div class="form-row">
@forelse($priceLists as $list)
                <div class="form-col" style="background: #f8f9fa; padding: 15px; border-radius: 6px; border: 1px solid var(--border-color); min-width: 250px;">
                    <h4 style="margin-top: 0; margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 8px; color: var(--primary-color);">
                        {{ $list->name }}
                    </h4>
                    
                    <div class="form-group">
                        <label for="cost_price_{{ $list->id }}">{{ __('messages.dashboard.products.pricing.form.cost_price') }} ($)</label>
                        <input type="number" step="0.01" min="0" 
                               name="prices[{{ $list->id }}][cost_price]" 
                               id="cost_price_{{ $list->id }}" 
                               class="form-control" 
                               value="{{ old('prices.'.$list->id.'.cost_price', isset($existingPrices[$list->id]) ? $existingPrices[$list->id]->cost_price : '') }}" 
                               placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label for="sale_price_{{ $list->id }}">{{ __('messages.dashboard.products.pricing.form.sale_price') }} ($)</label>
                        <input type="number" step="0.01" min="0" 
                               name="prices[{{ $list->id }}][sale_price]" 
                               id="sale_price_{{ $list->id }}" 
                               class="form-control" 
                               value="{{ old('prices.'.$list->id.'.sale_price', isset($existingPrices[$list->id]) ? $existingPrices[$list->id]->sale_price : '') }}" 
                               placeholder="0.00">
                    </div>

                    <div class="form-group">
                        <label for="discount_{{ $list->id }}">{{ __('messages.dashboard.products.pricing.form.discount') }} ($)</label>
                        <input type="number" step="0.01" min="0" 
                               name="prices[{{ $list->id }}][discount]" 
                               id="discount_{{ $list->id }}" 
                               class="form-control" 
                               value="{{ old('prices.'.$list->id.'.discount', isset($existingPrices[$list->id]) ? $existingPrices[$list->id]->discount : '') }}" 
                               placeholder="0.00">
                    </div>
                    
                    @if(isset($existingPrices[$list->id]))
                        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 10px;">
                            {{ __('messages.dashboard.products.pricing.form.current_margin') }}: <strong style="color: {{ $existingPrices[$list->id]->margin > 0 ? 'green' : 'red' }}">
                                {{ number_format($existingPrices[$list->id]->margin, 2) }}%
                            </strong>
                        </div>
                    @endif
                </div>
            @empty
                <div style="padding: 10px; color: var(--text-muted); width: 100%;">
                    <em>No Price Lists configured in the system. (You may need to seed or create default Price Lists like 'Retail' and 'Wholesale').</em>
                </div>
            @endforelse
        </div>

        @if($priceLists->count() > 0)
        <div class="form-group form-group-send">
            <x-bmdc-button type="submit" variant="raised">
                {{ __('messages.dashboard.products.form.save_base_prices') }}
            </x-bmdc-button>
        </div>
        @endif
    </form>
</div>

<!-- QUANTITY RULES MODULE -->
<div style="display: flex; gap: 20px; flex-wrap: wrap;">
    
    <!-- Add Rule Form -->
    <div class="module-card" style="flex: 1; min-width: 300px;">
        <h3 style="margin-top: 0; margin-bottom: 20px; color: var(--primary-color);">{{ __('messages.dashboard.products.pricing.form.rules.title') }}</h3>
        <form action="{{ route('admin.products.rules.store', $product->id) }}" method="POST">
            @csrf

            <div class="form-group">
                <label>{{ __('messages.dashboard.products.pricing.form.rules.rule_name') }} *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required autocomplete="off">
            </div>

            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label>{{ __('messages.dashboard.products.pricing.form.rules.minimum_qty') }} *</label>
                        <input type="number" step="0.01" min="0.01" name="minimum_quantity" class="form-control" value="{{ old('minimum_quantity') }}" required autocomplete="off">
                    </div>
                </div>
                <div class="form-col">
                    <div class="form-group">
                        <label>{{ __('messages.dashboard.products.pricing.form.rules.rule_type') }} *</label>
                        <select name="rule_type" class="form-control" required>
                            <option value="fixed_price">{{ __('messages.dashboard.products.pricing.form.rules.rule_type_fixed_price') }}</option>
                            <option value="unit_discount">{{ __('messages.dashboard.products.pricing.form.rules.rule_type_unit_discount') }}</option>
                            <option value="percentage">{{ __('messages.dashboard.products.pricing.form.rules.rule_type_percentage') }}</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-col">
                    <div class="form-group">
                        <label>{{ __('messages.dashboard.products.pricing.form.rules.discount_price_value') }}*</label>
                        <input type="number" step="0.01" min="0" name="discount_value" class="form-control" value="{{ old('discount_value') }}" required autocomplete="off">
                    </div>
                </div>
                <div class="form-col">
                    <div class="form-group">
                        <label>{{ __('messages.dashboard.products.pricing.form.rules.target_price_list') }}</label>
                        <select name="price_list_id" class="form-control">
                            <option value="">-- {{ __('messages.dashboard.products.pricing.form.rules.price_list_apply_to_all') }} --</option>
                            @foreach($priceLists as $list)
                                <option value="{{ $list->id }}">{{ $list->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" checked>
                    {{ __('messages.dashboard.products.pricing.form.rules.rule_active') }}
                </label>
            </div>
            <x-bmdc-button type="submit" variant="raised" style="width: 100%">
                {{ __('messages.dashboard.products.pricing.form.rules.add_rule_button') }}
            </x-bmdc-button>
        </form>
    </div>

    <!-- Active Rules List -->
    <div class="module-card" style="flex: 2; min-width: 400px;" data-jscontroller="PricingUIController">
        <h3 style="margin-top: 0; margin-bottom: 20px; color: var(--primary-color);">{{ __('messages.dashboard.products.pricing.form.rules.active_pricing_rules') }}</h3>
        
        <table class="data-table">
            <thead>
                <tr>
                    <th>{{ __('messages.dashboard.products.pricing.form.rules.table.name') }}</th>
                    <th>{{ __('messages.dashboard.products.pricing.form.rules.table.minimum_qty') }}</th>
                    <th>{{ __('messages.dashboard.products.pricing.form.rules.table.type_value') }}</th>
                    <th>{{ __('messages.dashboard.products.pricing.form.rules.table.price_list') }}</th>
                    <th>{{ __('messages.dashboard.products.pricing.form.rules.table.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rules as $rule)
                <tr>
                    <td><strong>{{ $rule->name }}</strong></td>
                    <td>{{ (float) $rule->minimum_quantity }}</td>
                    <td>
                        @if($rule->rule_type == 'percentage')
                            {{ (float) $rule->discount_value }}% Off
                        @elseif($rule->rule_type == 'unit_discount')
                            -${{ number_format($rule->discount_value, 2) }} / unit
                        @else
                            ${{ number_format($rule->discount_value, 2) }} total
                        @endif
                    </td>
                    <td>{{ $rule->priceList ? $rule->priceList->name : 'All' }}</td>
                    <td>
                        <form action="{{ route('admin.products.rules.destroy', [$product->id, $rule->id]) }}" method="POST" onsubmit="requireModule('PricingUIController').confirmRuleDeletion(event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" style="padding: 4px 8px; font-size: 0.8rem;">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 20px;">
                        No se han configurado reglas de cantidad o compra al por mayor para este producto.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection