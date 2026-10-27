<?php

return [
    'view' => 'ver',
    'edit' => 'editar',
    'create' => 'crear',
    'delete' => 'eliminar',
    'update' => 'actualizar',
    'logout' => 'Cerrar sessión',
    'auth' => [
        'welcome_back' => 'Bienvenido de nuevo',
        'poin_of_sale' => 'Punto de venta',
        'signin_to_continue' => 'Inicia sesión en tu cuenta para ingresar al punto de venta.',
        'email' => 'Correo electrónico',
        'password' => 'Contraseña',
        'signin_button' => 'Iniciar sesión'
    ],
    'business_categories' => [
        'grocery' => 'Tienda de abarrotes',
        'convenience_store' => 'Tienda de conveniencia',
        'pharmacy' => 'Farmacia',
        'restaurant' => 'Restaurante',
        'hardware_store' => 'Tienda de hardware',
        'clothing_store' => 'Tienda de ropa',
        'other' => 'Otras'
    ],
    'roles' => [
        'admin' => [
            'code' => 'admin',
            'name' => 'Administrador',
            'description' => 'Acceso administrativo completo al sistema POS.'
        ],
        'user' => [
            'code' => 'employee',
            'name' => 'Empleado',
            'description' => 'Rol estándar de cajero y operador de piso.'
        ]
    ],
    'promotions' => [
        'single' => [
            'name' => 'Individual'
        ],
        'pack' => [
            'name' => 'Paquete'
        ],
        'bulk' => [
            'name' => 'Mayoreo'
        ]
    ],
    'measurement_units' => [
        'piece' => [
            'name' => 'pieza',
            'symbol' => 'pza'
        ],
        'box' => [
            'name' => 'caja',
            'symbol' => 'XBX - Caja'
        ],
        'package' => [
            'name' => 'paquete',
            'symbol' => 'paq'
        ],
        'bulk' => [
            'name' => 'bulto',
            'symbol' => 'bulto'
        ],
        'dozen' => [
            'name' => 'docena',
            'symbol' => 'doc'
        ],
        'roll' => [
            'name' => 'rollo',
            'symbol' => 'rollo'
        ],
        'kilogram' => [
            'name' => 'kilogramo',
            'symbol' => 'kg'
        ],
        'gram' => [
            'name' => 'gramo',
            'symbol' => 'g'
        ],
        'milligram' => [
            'name' => 'miligramo',
            'symbol' => 'mg'
        ],
        'miligram' => [
            'name' => 'miligramo',
            'symbol' => 'mg' 
        ],
        'pound' => [
            'name' => 'libra',
            'symbol' => 'lb'
        ],
        'ounce' => [
            'name' => 'onza',
            'symbol' => 'oz'
        ],
        'liter' => [
            'name' => 'litro',
            'symbol' => 'l'
        ],
        'milliliter' => [
            'name' => 'mililitro',
            'symbol' => 'ml'
        ],
        'gallon' => [
            'name' => 'galón',
            'symbol' => 'gal'
        ],
        'meter' => [
            'name' => 'metro',
            'symbol' => 'm'
        ],
        'centimeter' => [
            'name' => 'centímetro',
            'symbol' => 'cm'
        ],
        'kilometer' => [
            'name' => 'kilómetro',
            'symbol' => 'km'
        ],
        'hour' => [
            'name' => 'hora',
            'symbol' => 'hr'
        ],
        'service' => [
            'name' => 'servicio',
            'symbol' => 'serv'
        ]
    ],
    'dashboard' => [
        'sidebar' => [
            'link_dashboard' => 'Panel de control',
            'link_pos' => 'Punto de Venta',
            'link_business' => 'Negocio',
            'link_stores' => 'Tiendas',
            'link_departments' => 'Departamentos',
            'link_products' => 'Productos',
            'link_inventory' => 'Inventario',
            'link_sales' => 'Ventas',
            'link_shifts_cash_ups' => 'Turnos y cierres de caja',
            'link_quotations' => 'Cotizaciones',
            'link_customers' => 'Clientes',
            'link_users' => 'Usuarios',
            'link_suppliers' => 'Proveedores',
            'link_credits' => 'Créditos',
            'link_reports' => 'Reportes',
            'link_notifications' => 'Notificaciones',
            'link_settings' => 'Configuración'
        ],
        'business' => [
            'updated_successfully' => 'Perfil del negocio actualizado correctamente.',
            'headline' => 'Perfil del negocio',
            'description' => 'Gestiona la información principal de tu negocio y tu imagen de marca.',
            'form' => [
                'business_name' => 'Nombre del negocio',
                'legal_name' => 'Nombre legal (Razón social)',
                'rfc' => 'RFC',
                'business_category' => 'Categoría del negocio',
                'select_category' => 'Selecciona una categoría',
                'description' => 'Descripción / Biografía',
                'phone_number_1' => 'Número de teléfono #1',
                'phone_number_2' => 'Número de teléfono #2',
                'email' => 'Correo electrónico',
                'website' => 'Sitio web',
                'default_currency' => 'Moneda predeterminada',
                'timezone' => 'Zona Horaría',
                'logo' => 'Logotipo del negocio',
                'logo_label' => 'Haz click para agregar imagen',
                'save' => 'Guardar cambios'
            ]
        ],
        'pos_terminals' => [
            'created_successfully' => 'Terminal POS creada con éxito.',
            'updated_successfully' => 'Terminal POS actualizada con éxito.',
            'deleted_successfully' => 'Terminal POS eliminada con éxito.',
            'headline' => 'Terminales POS',
            'description' => 'Terminales registradas en esta ubicación.',
            'headline_create' => 'Registar nueva terminal POS',
            'description_create' => 'Sucursal',
            'headline_edit' => 'Editar terminal',
            'description_edit' => 'Sucursal',
            'form' => [
                'code' => 'Código de terminal (ej., CAJA-01)',
                'name' => 'Nombre de la terminal',
                'name_placeholder' => 'ej., Registro principal',
                'target_printer' => 'Nombre de la impresora de tickets de destino',
                'target_printer_placeholder' => 'Dejar en blanco para usar la predeterminada del dispositivo.',
                'target_printer_description' => 'Debe coincidir exactamente con el nombre de la impresora en el sistema operativo si se utiliza el enrutamiento de impresión local.',
                'terminal_active_description' => 'La terminal está activa y se permite conectar',
                'save_terminal' => 'Guardar terminal',
                'update_terminal' => 'Actualizar terminal'
            ],
            'table' => [
                'code' => 'Código',
                'name' => 'Nombre',
                'printer' => 'Impresora de destino',
                'last_seen' => 'Última conexión',
                'status' => 'Status',
                'actions' => 'Acciones'
            ],
            'delete' => 'Eliminar terminal',
            'printer_default' => 'Predeterminada del sistema',
            'status_never_connected' => 'Nunca conectada',
            'new_terminal_button' => 'Nueva terminal',
            'back_to_terminals_button' => 'Volver  a las terminales'
        ],
        'stores' => [
            'updated_successfully' => 'Tienda actualizada con éxito.',
            'created_successfully' => 'Tienda creada con éxito.',
            'deleted_successfully' => 'Tienda eliminada con éxito.',
            'headline' => 'Tiendas',
            'description' => 'Gestiona tus ubicaciones físicas y sucursales.',
            'back' => 'Volver atrás',
            'edit_store' => 'Editar tienda',
            'form' => [
                'store_code' => 'Código de la tienda',
                'store_name' => 'Nombre de la tienda',
                'phone' => 'Número de teléfono',
                'email' => 'Correo electrónico',
                'address' => 'Dirección',
                'city' => 'Ciudad',
                'state' => 'Estado',
                'manager' => 'Gerente de la tienda',
                'mark_as_active' => 'Marcar como activa',
                'update' => 'Actualizar tienda',
                'create' => 'Crear tienda'
            ],
            'table' => [
                'code' => 'Código',
                'name' => 'Nombre',
                'city' => 'Ciudad',
                'manager' => 'Encargado',
                'status' => 'Status',
                'actions' => 'Acciones'
            ],
            'cards' => [
                'pos_terminals' => [
                    'title' => 'Terminales POS',
                    'description' => 'Terminales registradas en esta ubicación.',
                    'status_online' => 'En línea',
                    'status_offline' => 'Sin línea',
                    'no_terminals_configured' => 'No hay terminales configuradas.',
                    'manage_terminals' => 'Administrar terminales',
                ]
            ],
            'delete' => 'Eliminar tienda',
            'unassigned' => 'Sin asignar',
            'new_store_button' => 'Nueva tienda',
            'no_stores_found' => 'No se encontró ninguna tienda.',
            'status_active' => 'Activa',
            'status_inactive' => 'Inactiva'
        ],
        'departments' => [
            'updated_successfully' => 'Departamento actualizado con éxito.',
            'created_successfully' => 'Departamento creado con éxito.',
            'deleted_successfully' => 'Departamento eliminado con éxito.',
            'headline' => 'Departamentos',
            'description' => 'Organiza tus productos en departamentos de alto nivel.',
            'headline_create' => 'Registar un nuevo departamento',
            'description_create' => 'Sucursal',
            'headline_edit' => 'Editar terminal',
            'description_edit' => 'Sucursal',
            'back' => 'Volver atrás',
            'edit_departments' => 'Editar departamento',
            'form' => [
                'code' => 'Código del departamento',
                'name' => 'Nombre del departamento',
                'description' => 'Descripción',
                'phone' => 'Número de teléfono',
                'email' => 'Correo electrónico',
                'responsable' => 'Persona responsable',
                'mark_as_active' => 'Activar departamento',
                'update' => 'Actualizar departamento',
                'create' => 'Crear departmento'
            ],
            'table' => [
                'code' => 'Código',
                'name' => 'Nombre',
                'responsable' => 'Persona responsable',
                'email' => 'Correo electrónico',
                'status' => 'Estatus',
                'actions' => 'Acciones'
            ],
            'delete' => 'Eliminar departamento',
            'unassigned' => 'Sin asignar',
            'new_department_button' => 'Nuevo departamento',
            'no_departments_found' => 'No se encontró ningún departamento',
            'status_active' => 'Activo',
            'status_inactive' => 'Inactivo'
        ],
        'products' => [
            'updated_successfully' => '',
            'created_successfully' => '',
            'headline' => 'Directorio de productos',
            'description' => 'Gestiona tu catálogo de inventario, el escaneo y los precios.',
            'headline_create' => 'Registar un nuevo producto',
            'description_create' => '',
            'headline_edit' => 'Editar producto',
            'description_edit' => '',
            'search_placeholder' => 'Escanee el código de barras o escriba el nombre/SKU para buscar...',
            'search_button' => 'buscar',
            'search_clear' => 'Limpiar',
            'back' => 'Volver atrás',
            'form' => [
                'barcode' => 'Código de barras principal (escanear o generar)',
                'barcode_placeholder' => 'Escanee un artículo ahora...',
                'barcode_generate_internal' => 'Generar internamente',
                'barcode_helper_text' => 'Utilice "Generar internamente" para artículos que no tengan códigos de barras del fabricante (por ejemplo, productos agrícolas a granel).',
                'sku' => 'SKU (Dejar en blanco para generar automáticamente)',
                'product_name' => 'Nombre del producto',
                'department' => 'Departamento',
                'select_department' => 'Selecciona un departamento',
                'category' => 'Categoría',
                'select_category' => 'Selecciona una categoría',
                'product_type' => 'Tipo de producto',
                'product_type_standard' => 'Artículo estándar',
                'product_type_service' => 'Servicio (no de inventario)',
                'product_type_kit' => 'Kit / Paquete',
                'uom' => 'Unidad de medida de venta (UM)',
                'select_uom' => 'Selecciona una uidad de medida',
                'uomi' => 'Unidad de medida de inventario',
                'allow_fractional_qty' => 'Permitir cantidades fraccionarias (p. ej., 1,5 kg)',
                'track_inventory' => 'Seguimiento de inventario',
                'product_is_active' => 'Producto activo',
                'create' => 'Crear producto',
                'update' => 'Actualizar producto',
                'save_and_continue' => 'Guardar y continuar a los precios',
                'save_base_prices' => 'Guardar precios base'
            ],
            'table' => [
                'sku' => 'SKU',
                'name' => 'Nombre',
                'department' => 'Departamento',
                'uom' => 'Unidad de medida de venta (UM)',
                'status' => 'Estatus',
                'actions' => 'Acciones'
            ],
            'cards' => [
                'images' => [
                    'title' => 'Imágenes del producto',
                    'description' => 'Sube fotos cuadradas (relación de aspecto 1:1). (Se redimensionan automáticamente)',
                    'upload_button' => 'Subir',
                    'no_uploaded_images' => 'No se han subido imágenes.',
                ],
                'barcodes' => [
                    'title' => 'Códigos de barras asociados',
                    'description' => 'Gestionar los códigos de escaneo de este artículo.',
                    'primary' => 'Principal',
                    'no_barcodes_assigned' => 'No se han asignado códigos de barras.',
                    'manage_barcodes' => 'Administrar códigos de barras'
                ],
                'pricing' => [
                    'title' => 'Precios y normas',
                    'description' => 'Establezca precios base y reglas para cantidades al por mayor.',
                    'info' => 'Consulte o modifique las listas de precios actuales y los márgenes calculados automáticamente.',
                    'manage_pricing' => 'Administrar precios'
                ]
            ],
            'pricing' => [
                'headline' => 'Administrar precios',
                'form' => [
                    'base_prices' => 'Precios base',
                    'cost_price' => 'Precio de costo',
                    'sale_price' => 'Precio de venta',
                    'discount' => 'Descuento',
                    'current_margin' => 'Margen actual',
                    'rules' => [
                        'title' => 'Añadir regla de cantidad',
                        'rule_name' => 'Nombre de la regla (p. ej., "Compra 3 por $10")',
                        'minimum_qty' => 'Cantidad mínima',
                        'rule_type' => 'Tipo de regla',
                        'rule_type_fixed_price' => 'Precio fijo (total)',
                        'rule_type_unit_discount' => 'Descuento por unidad ($)',
                        'rule_type_percentage' => 'Porcentaje de descuento (%)',
                        'discount_price_value' => 'Descuento/Valor del precio',
                        'target_price_list' => 'Lista de precios objetivo',
                        'price_list_apply_to_all' => 'Aplicar a todo / Predeterminado',
                        'rule_active' => 'Regla activa',
                        'add_rule_button' => 'Agregar regla',
                        'active_pricing_rules' => 'Reglas de precios activas',
                        'table' => [
                            'name' => 'Nombre',
                            'minimum_qty' => 'Cantidad mínima',
                            'type_value' => 'Tipo / Valor',
                            'price_list' => 'Lista de precios',
                            'actions' => 'Acciones'
                        ]
                    ]
                ]
            ],
            'delete' => 'Eliminar producto',
            'unassigned' => 'Sin asignar',
            'new_product_button' => 'Nuevo producto',
            'no_products_found' => 'No se encontraron productos que coincidan con sus criterios.',
            'status_active' => 'Activo',
            'status_inactive' => 'Inactivo'
        ],
        'promotions' => [
            'back' => 'Volver atrás',
            'delete' => 'Eliminar promoción'
        ]
    ],
    'validations' => [
        'business' => [
            'form_fields' => [
                'store_name' => 'nombre del negocio',
                'legal_name' => 'nombre legal (razón social)',
                'rfc' => 'rfc',
                'store_category' => 'categoría del negocio',
                'bio' => 'descripción',
                'phone_line_1' => 'número de teléfono #1',
                'phone_line_2' => 'número de teléfono #2',
                'website' => 'sitio web',
                'logo' => 'logotipo del negocio'
            ],
            'name_required' => 'El nombre del negocio es obligatorio.',
            'rfc_required' => 'El RFC es obligatorio.',
            'category_id_invalid' => 'La categoría de negocio seleccionada no es válida.',
            'email_valid' => 'Por favor, proporcione una dirección de correo electrónico válida.',
            'website_url_valid' => 'Por favor, proporcione una URL válida (p. ej., https://example.com).',
            'logo_valid' => 'El archivo cargado debe ser una imagen.',
            'logo_valid_extension' => 'El logotipo debe ser un archivo de tipo: jpeg, png o jpg.',
            'logo_max_size' => 'El logotipo no debe superar los 2 MB.'
        ],
        'stores' => [
            'code_required' => 'El código de la tienda es obligatorio.',
            'code_unique' => 'Este código de tienda ya está en uso. Por favor, elija un identificador único.',
            'name_required' => 'El nombre de la tienda es obligatorio.',
            'store_manager_exists' => 'El gerente de tienda seleccionado no existe en el sistema.',
            'email_valid' => 'Por favor, proporcione una dirección de correo electrónico válida para la tienda.'
        ]
    ]
];
