@props(['name', 'excludeId' => '', 'initialValue' => '', 'initialText' => '', 'departments' => [], 'categories' => []])

<div class="dynamic-product-search" id="dps_{{ $name }}" data-jscontroller="DynamicProductSearch" data-endpoint="{{ route('admin.products.search_components') }}" data-exclude="{{ $excludeId }}" style="position: relative;">
    
    <input type="hidden" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $initialValue) }}" data-jsbind="hidden_id">

    <div class="search-input-group">
        <input type="text" class="form-control @error($name) is-invalid @enderror" placeholder="Buscar producto por nombre, SKU o código..." data-jskeyup="@handleInput" data-jsfocus="@handleFocus" data-jsbind="search_input" autocomplete="off" value="{{ $initialText }}">
    </div>

    <!-- Filter Panel (Collapsible) -->
    <div style="background: #f9fafb; padding: 10px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 6px 6px; display: flex; gap: 10px; flex-wrap: wrap;">
        <select data-jschange="@handleFilterChange" data-jsbind="filter_department" class="form-control" style="flex: 1; min-width: 150px; font-size: 0.85rem; padding: 4px;">
            <option value="">-- Todos los Deptos --</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}">{{ $dept->name }}</option>
            @endforeach
        </select>

        <select data-jschange="@handleFilterChange" data-jsbind="filter_category" class="form-control" style="flex: 1; min-width: 150px; font-size: 0.85rem; padding: 4px;">
            <option value="">-- Todas las Categorías --</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>

        <label style="display: flex; align-items: center; gap: 4px; font-size: 0.85rem;">
            <input type="checkbox" data-jschange="@handleFilterChange" data-jsbind="filter_fractional" value="1">
            <span>Permite Fracción</span>
        </label>
        
        <label style="display: flex; align-items: center; gap: 4px; font-size: 0.85rem;">
            <input type="checkbox" data-jschange="@handleFilterChange" data-jsbind="filter_active" value="1" checked>
            <span>Solo Activos</span>
        </label>
    </div>

    <!-- Dropdown Results -->
    <div data-jsbind="dropdown" style="display: none; position: absolute; top: 100%; left: 0; right: 0; background: #fff; border: 1px solid #d1d5db; border-radius: 4px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); z-index: 1000; max-height: 250px; overflow-y: auto; margin-top: 4px;">
        <div style="padding: 10px; text-align: center; color: #6b7280; font-size: 0.9rem;">Escriba para buscar...</div>
    </div>
</div>