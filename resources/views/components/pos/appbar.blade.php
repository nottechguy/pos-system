<div class="appbar" data-jsid="{'test_id' : 0}" data-jscontroller="POSAppbar" data-jscbi="[boot, 0, null, user_{{ auth()->user()->id }}]">
	<div class="appbar__container appbar__container-block">
		<div class="appbar__section appbar__section--meta">
			<span class="appbar__logo">
			</span>
			<span class="appbar__name">{{ config('app.name') }}</span>
		</div>
		<div class="appbar__section appbar__section--menu">
			<div class="appbar__flex">
		        <x-bmdc-button type="link" href="{{ route('admin.products.index') }}" variant="outlined">
		            <x-slot name="mIcon">
		                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M640-80q-100 0-170-70t-70-170q0-100 70-170t170-70q100 0 170 70t70 170q0 100-70 170T640-80Zm0-80q66 0 113-47t47-113q0-66-47-113t-113-47q-66 0-113 47t-47 113q0 66 47 113t113 47Zm-480 0q-33 0-56.5-23.5T80-240v-304q0-8 1.5-16t4.5-16l80-184h-6q-17 0-28.5-11.5T120-800v-40q0-17 11.5-28.5T160-880h280q17 0 28.5 11.5T480-840v40q0 17-11.5 28.5T440-760h-6l66 152q-19 10-36 21t-32 25l-84-198h-96l-92 216v304h170q5 21 13.5 41.5T364-160H160Zm480-440q-42 0-71-29t-29-71q0-42 29-71t71-29v200q0-42 29-71t71-29q42 0 71 29t29 71H640Z"></path></svg>
		            </x-slot>		            
		            Productos
		        </x-bmdc-button>

		        <x-bmdc-button type="link" href="{{ route('admin.inventory.index') }}" variant="outlined">
		            <x-slot name="mIcon">
		               <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M620-163 450-333l56-56 114 114 226-226 56 56-282 282Zm220-397h-80v-200h-80v120H280v-120h-80v560h240v80H200q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h167q11-35 43-57.5t70-22.5q40 0 71.5 22.5T594-840h166q33 0 56.5 23.5T840-760v200ZM508.5-771.5Q520-783 520-800t-11.5-28.5Q497-840 480-840t-28.5 11.5Q440-817 440-800t11.5 28.5Q463-760 480-760t28.5-11.5Z"></path></svg>
		            </x-slot>		            
		            Inventario
		        </x-bmdc-button>

		        <x-bmdc-button type="link" href="{{ route('admin.scale.index') }}" variant="outlined">
		            <x-slot name="mIcon">
		              <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#FFFFFF"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 2.81 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-2.77-.01-2.2-1.9-2.96-3.66-3.42z"/></svg>
		            </x-slot>		            
		            Ventas
		        </x-bmdc-button>
			</div>
		</div>
		
		<!-- Cashier Component Integration -->
		<x-pos.cashier />
	</div>
</div>