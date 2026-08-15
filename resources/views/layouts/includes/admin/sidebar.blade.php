  @php
      $links = [
          [
              'header' => 'Principal',
          ],
          [
              'name' => 'Dashboard',
              'icon' => 'fa-solid fa-gauge',
              'href' => route('admin.dashboard'),
              'active' => request()->routeIs('admin.dashboard'), //El objeto request() nos permite acceder a la información de la petición actual y comprobar si estamos en esa ruta
          ],

          [
              'name' => 'Inventario',
              'icon' => 'fa-solid fa-boxes-stacked',
              'active' => request()->routeIs(['admin.categories.*', 'admin.products.*', 'admin.warehouses.*']),
              'submenu' => [
                  [
                      'name' => 'Categorias',
                      'icon' => 'fa-solid fa-list',
                      'href' => route('admin.categories.index'),
                      'active' => request()->routeIs('admin.categories.*'),
                  ],
                  [
                      'name' => 'Productos',
                      'icon' => 'fa-solid fa-box',
                      'href' => route('admin.products.index'),
                      'active' => request()->routeIs('admin.products.*'),
                  ],
                  [
                      'name' => 'Almacenes',
                      'icon' => 'fa-solid fa-warehouse',
                      'href' => route('admin.warehouses.index'),
                      'active' => request()->routeIs('admin.warehouses.*'),
                  ],
              ],
          ],

          [
              'name' => 'Compras',
              'icon' => 'fa-solid fa-cart-shopping',
              'active' => request()->routeIs([
                'admin.suppliers.*', 
                'admin.purchase-orders.*', 
                'admin.purchases.*']),
              'submenu' => [
                  [
                      'name' => 'Proveedores',
                      'href' => route('admin.suppliers.index'),
                      'active' => request()->routeIs('admin.suppliers.*'),
                  ],
                  [
                      'name' => 'Ordenes de compra',
                      'href' => route('admin.purchase-orders.index'),
                      'active' => request()->routeIs('admin.purchase-orders.*'),
                  ],
                  [
                      'name' => 'Compras',
                      'href' => route('admin.purchases.index'),
                      'active' => request()->routeIs('admin.purchases.*'),
                  ],
              ],
          ],

          [
              'name' => 'Ventas',
              'icon' => 'fa-solid fa-cash-register',
              'active' => request()->routeIs([
                'admin.customers.*', 
                'admin.quotes.*', 
                'admin.sales.*'
                ]),
              'submenu' => [
                  [
                      'name' => 'Clientes',
                      'icon' => 'fa-solid fa-users',
                      'href' => route('admin.customers.index'),
                      'active' => request()->routeIs('admin.customers.*'),
                  ],
                  [
                      'name' => 'Cotizaciones',
                      'href' => route('admin.quotes.index'),
                      'active' => request()->routeIs('admin.quotes.*'),
                  ],
                  [
                      'name' => 'Ventas',
                      'href' => route('admin.sales.index'),
                      'active' => request()->routeIs('admin.sales.*'),
                  ],
              ],
          ],

          [
              'name' => 'Movimientos',
              'icon' => 'fa-solid fa-arrows-rotate',
              'active' => request()->routeIs([
                'admin.movements.*'
                ]),
              'submenu' => [
                  [
                      'name' => 'Entradas y salidas',
                      'href' => route('admin.movements.index'),
                      'active' => request()->routeIs('admin.movements.*'),
                  ],
                  [
                      'name' => 'Transferencias',
                      'href' => '',
                      'active' => false,
                  ],
              ],
          ],

          [
              'name' => 'Reportes',
              'icon' => 'fa-solid fa-chart-line',
              'active' => false,
              'href' => '',
          ],

          [
              'header' => 'Configuracion',
          ],

          [
              'name' => 'Usuarios',
              'icon' => 'fa-solid fa-user',
              'href' => '',
              'active' => false,
          ],

          [
              'name' => 'Roles',
              'icon' => 'fa-solid fa-shield-halved',
              'href' => '',
              'active' => false,
          ],

          [
              'name' => 'Permisos',
              'icon' => 'fa-solid fa-lock',
              'href' => '',
              'active' => false,
          ],

          [
              'name' => 'Ajustes',
              'icon' => 'fa-solid fa-gear',
              'href' => '',
              'active' => false,
          ],

          //EJEMPLOS DE OTROS MENUS DINAMICOS, DESARROLLADOS MAS ADELANTE
          //   [
          //       'header' => 'Administrar página',
          //   ],

          //   [
          //       'name' => 'Compra',
          //       'icon' => 'fa-solid fa-basket-shopping',
          //       'href' => route('admin.dashboard'),
          //       'active' => false,
          //       'submenu' => [
          //           [
          //               'name' => 'Productos',
          //               'href' => '#',
          //               'active' => false,
          //           ],
          //           [
          //               'name' => 'Categorias',
          //               'href' => '#',
          //               'active' => false,
          //           ],
          //           [
          //               'name' => 'Imagenes',
          //               'href' => '#',
          //               'active' => false,
          //           ],
          //       ],
          //   ],
      ];

  @endphp


  <aside id="logo-sidebar"
      class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
      aria-label="Sidebar">
      <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
          <ul class="space-y-2 font-medium">
              @foreach ($links as $link)
                  <li>
                      @isset($link['header'])
                          <div class="px-2 py-2 text-xs font-semibold text-gray-500 uppercase">
                              {{ $link['header'] }}
                          </div>
                      @else
                          {{-- La plantilla que hemos cogido de Flowbite, usa un id predefinido para la apertudra y cierre del submenu id="dropdown-example". No funciona bien con la estructura de submenús quye
                            hemos creado. Por ello envolvemos la plantilla en un div y inicializamos la libreria js Alpine que permite crear interacciones de forma sencilla. Para ello añadimos al div ---> x-data y definimos una variable.
                            Si la variable es true, se abre el submenu, si es false, se cierra. El valor de open dependerá del contenido del active de cada submenún. Tambien de añadir al <ul> el atributo x-show="open" y x-cloak --}}
                          @isset($link['submenu'])
                              {{-- Contenedor Alpine --}}
                              <div x-data="{
                                  open: {{ $link['active'] ? 'true' : 'false' }}
                              }">
                                  <button type="button" @click="open = !open" {{--cambia el estado de la variable open al clicar--}}
                                      class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700">

                                      <span class="w-6 h-6 inline-flex justify-center items-center text-gray-500">
                                          <i class="{{ $link['icon'] }}"></i> {{-- ICONO PRINCIPAL --}}
                                      </span>

                                      <span
                                          class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">{{ $link['name'] }}</span>

                                      {{-- ICONO FLECHA CORREGIDO --}}
                                      <i class="fa-solid text-sm ml-auto"
                                          :class="{ 'fa-chevron-up': open, 'fa-chevron-down': !open }"></i>
                                  </button>

                                  {{-- Submenú con x-cloak corregido --}}
                                  <ul x-show="open" x-cloak class="py-2 space-y-2">
                                      @foreach ($link['submenu'] as $submenu)
                                          <li>
                                              <a href="{{ $submenu['href'] }}"
                                                  class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700 {{$submenu['active'] ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                                  {{ $submenu['name'] }}
                                              </a>
                                          </li>
                                      @endforeach
                                  </ul>
                              </div>
                          @else
                              <a href="{{ $link['href'] }}"
                                  class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group {{ $link['active'] ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                  <span class="w-6 h-6 inline-flex justify-center items-center text-gray-500">
                                      <i class="{{ $link['icon'] }}"></i>
                                  </span>
                                  <span class="ms-3">{{ $link['name'] }}</span>
                              </a>
                          @endisset
                      @endisset
                  </li>
              @endforeach
          </ul>
      </div>
  </aside>
