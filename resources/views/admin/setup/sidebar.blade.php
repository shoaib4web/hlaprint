 <!-- ======== sidebar-nav start =========== -->
 <aside class="sidebar-nav-wrapper">
     <div class="navbar-logo">
         <a href="{{ url('dashboard') }}">
             <img style="height:4.5rem" src="{{ asset('public/admin-assets/images/logo/logo.png') }}" alt="logo" />
         </a>
     </div>
     <nav class="sidebar-nav">
         <ul>
             <li class="nav-item">
                 <a href="{{ url('dashboard') }}">
                     <span class="icon mdi mdi-view-dashboard">
                     </span>
                     <span class="text">Dashboard</span>
                 </a>
             </li>

             @if (Auth::user()->role == 'superadmin' )
                 {{-- user list start --}}
                 <li class="nav-item">
                    <a href="{{ url('financials') }}">
                        <span class="icon mdi mdi-view-dashboard">
                        </span>
                        <span class="text">Financials</span>
                    </a>
                </li>
                 <li class="nav-item nav-item-has-children">
                     <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_2"
                         aria-controls="ddmenu_2" aria-expanded="false" aria-label="Toggle navigation">
                         <span class="icon mdi mdi-account-circle">
                         </span>
                         <span class="text">Users</span>
                     </a>
                     <ul id="ddmenu_2" class="collapse dropdown-nav">
                         <li>
                             <a href="{{ url('create-user') }}"> Create Users </a>
                         </li>
                         <li>
                             <a href="{{ url('users') }}"> All Users </a>
                         </li>
                     </ul>
                 </li>
                 {{-- user list end --}}

                 {{-- Transaction start --}}
                 <li class="nav-item nav-item-has-children">
                    <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#transactionMenu"
                        aria-controls="transactionMenu" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="icon mdi mdi-bank">
                        </span>
                        <span class="text">Transactions</span>
                    </a>
                    <ul id="transactionMenu" class="collapse dropdown-nav">
                        <li>
                            <a href="{{ url('transaction') }}"> Transactions </a>
                        </li>
                    </ul>
                </li>
                {{-- Transaction end --}}

                 {{-- shop start --}}
                 <li class="nav-item nav-item-has-children">
                     <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#shopMenu"
                         aria-controls="shopMenu" aria-expanded="false" aria-label="Toggle navigation">
                         <span class="icon mdi mdi-cart-outline"></span>
                         <span class="text">Shops</span>
                     </a>
                     <ul id="shopMenu" class="collapse dropdown-nav">
                     @if (Auth::user()->role == 'superadmin' )
                         <li>
                             <a href="{{ url('create-shop') }}"> Create Shop </a>
                         </li>

                    @endif
                    <li>
                             <a href="{{ url('shops') }}"> Shop </a>
                         </li>
                         <li>
                             <a href="{{ url('getPrinters') }}"> Select Printer </a>
                         </li>
                     </ul>
                 </li>
                 {{-- shop end --}}
                 <li class="nav-item nav-item-has-children">
                     <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#invoiceMenu"
                         aria-controls="invoiceMenu" aria-expanded="false" aria-label="Toggle navigation">
                         <span class="icon mdi mdi-cart-outline"></span>
                         <span class="text">Invoice </span>
                     </a>
                     <ul id="invoiceMenu" class="collapse dropdown-nav">
                        <li>
                             <a href="{{ url('createInvoiceDetail') }}"> Invoice Management </a>
                         </li>
                         <!-- @if (Auth::user()->role == 'superadmin')
                         <li><form action ="{{ url('createInvoiceDetail') }}">
                            <input type="hidden" value="0" name="shop_id">
                             <button type="submit" > Admin Invoice Management </button>
                            </form>
                         </li>
                         @endif -->


                     </ul>
                 </li>


                 {{-- Color Size start --}}
                 <li class="nav-item nav-item-has-children">
                     <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#colorSizeMenu"
                         aria-controls="colorSizeMenu" aria-expanded="false" aria-label="Toggle navigation">
                         <span class="icon mdi mdi-format-color-fill"></span>
                         <span class="text">Pricing</span>
                     </a>
                     <ul id="colorSizeMenu" class="collapse dropdown-nav">
                         <li>
                             <a href="{{ url('price-option') }}"> Pricing </a>
                         </li>

                     </ul>
                 </li>
                 {{-- Color Size end --}}

                  {{-- Color Size start --}}
                  <li class="nav-item nav-item-has-children">
                    <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#printJobMenu"
                        aria-controls="printJobMenu" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="icon mdi mdi-printer-3d-nozzle-outline"></span>
                        <span class="text">Print Job</span>
                    </a>
                    <ul id="printJobMenu" class="collapse dropdown-nav">


                        <li>
                            <a href="{{ url('transPrintJob') }}"> Print Jobs </a>
                        </li>

                        <li>
                            <a href="{{ url('TransCashJob') }}"> Cash Orders </a>
                        </li>
                    </ul>
                </li>
                {{-- Color Size end --}}

             @elseif (Auth::user()->role == 'shopowner' || Auth::user()->role == 'shopmanager')
                 {{-- user list start --}}
                 @if (Auth::user()->role == 'shopowner')
                 <!-- <li class="nav-item nav-item-has-children">
                     <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#ddmenu_2"
                         aria-controls="ddmenu_2" aria-expanded="false" aria-label="Toggle navigation">
                         <span class="icon mdi mdi-account-circle">
                         </span>
                         <span class="text">Users</span>
                     </a>
                     <ul id="ddmenu_2" class="collapse dropdown-nav">

                         <li>
                             <a href="{{ url('create-user') }}"> Create Users </a>
                         </li>

                         <li>
                             <a href="{{ url('users') }}"> Users </a>
                         </li>

                     </ul>
                 </li> -->
                    <li class="nav-item nav-item-has-children">
                     <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#invoiceMenu"
                         aria-controls="invoiceMenu" aria-expanded="false" aria-label="Toggle navigation">
                         <span class="icon mdi mdi-cart-outline"></span>
                         <span class="text">Invoice </span>
                     </a>
                     <ul id="invoiceMenu" class="collapse dropdown-nav">
                        <li>
                             <a href="{{ url('createInvoiceDetail/'.Auth::user()->shop_id) }}"> Invoice Management </a>
                         </li>
                         <!-- @if (Auth::user()->role == 'superadmin')
                         <li><form action ="{{ url('createInvoiceDetail') }}">
                            <input type="hidden" value="0" name="shop_id">
                             <button type="submit" > Admin Invoice Management </button>
                            </form>
                         </li>
                         @endif -->


                     </ul>
                 </li>
                 <li class="nav-item nav-item-has-children">
                     <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#salesMenu"
                         aria-controls="salesMenu" aria-expanded="false" aria-label="Toggle navigation">
                         <span class="icon mdi mdi-cash-register">
                         </span>
                         <span class="text">Sales</span>
                     </a>
                     <ul id="salesMenu" class="collapse dropdown-nav">

                         <li>
                             <a href="{{ url('sales') }}"> Records </a>
                         </li>



                     </ul>
                 </li>
                 @endif
                 {{-- Transaction start --}}
                 <li class="nav-item nav-item-has-children">
                    <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#transactionMenu"
                        aria-controls="transactionMenu" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="icon mdi mdi-bank">
                        </span>
                        <span class="text">Transactions</span>
                    </a>
                    <ul id="transactionMenu" class="collapse dropdown-nav">
                        <li>
                            <a href="{{ url('transaction') }}"> Transactions </a>
                        </li>
                    </ul>
                </li>
                {{-- Transaction end --}}

                 {{-- user list end --}}

                 {{-- shop start --}}
                 @if (Auth::user()->role == 'shopowner')
                 <li class="nav-item nav-item-has-children">
                     <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#shopMenu"
                         aria-controls="shopMenu" aria-expanded="false" aria-label="Toggle navigation">
                         <span class="icon mdi mdi-bank-check"></span>
                         <span class="text">Financial Details</span>
                     </a>
                     <ul id="shopMenu" class="collapse dropdown-nav">
                     @if (Auth::user()->role == 'shopowner')
                         <li>
                         <a href="{{ url('financial-details') }}"> Input Details </a>
                         </li>

                    @endif


                     </ul>
                 </li>
                 @endif
                 {{-- shop end --}}



                 {{-- Color Size start --}}
                 <li class="nav-item nav-item-has-children">
                     <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#colorSizeMenu"
                         aria-controls="colorSizeMenu" aria-expanded="false" aria-label="Toggle navigation">
                         <span class="icon mdi mdi-format-color-fill"></span>
                         <span class="text">Pricing</span>
                     </a>
                     <ul id="colorSizeMenu" class="collapse dropdown-nav">
                         <li>
                             <a href="{{ url('/price-options') }}"> Prices </a>
                         </li>


                     </ul>
                 </li>
                 {{-- Color Size end --}}
                 <li class="nav-item nav-item-has-children">
                    <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#printJobMenu"
                        aria-controls="printJobMenu" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="icon mdi mdi-printer-3d-nozzle-outline"></span>
                        <span class="text">Print Job</span>
                    </a>
                    <ul id="printJobMenu" class="collapse dropdown-nav">


                        <li>
                            <a href="{{ url('transPrintJob') }}"> Print Jobs </a>
                        </li>

                        <li>
                            <a href="{{ url('TransCashJob') }}"> Cash Orders </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <div style="padding:7px 25px;display: flex;align-items: center;justify-content: space-around;">
                        <span class="icon mdi mdi-cloud-print-outline"></span>
                        <span class="text">System Status</span>
                        @if(GetShopStatus(getCurrentUsersShopId()))
                         <input style ="height:2rem;width:2rem" type="range" id="khttki_slider" min="0" max="1" step="1" value="1">
                         <span id="khttki_slider_text" class="green">ON</span>

                        @else
                        <input style ="height:2rem;width:2rem" type="range" id="khttki_slider" min="0" max="1" step="1" value="0">
                        <span id="khttki_slider_text" class="red">OFF</span>
                        @endif
                    </div>

                </li>

             @elseif(Auth::user()->role == 'cashier')
                  <li class="nav-item nav-item-has-children">
                    <a href="#0" class="collapsed" data-bs-toggle="collapse" data-bs-target="#printJobMenu"
                        aria-controls="printJobMenu" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="icon mdi mdi-printer-3d-nozzle-outline"></span>
                        <span class="text">Print Job</span>
                    </a>
                    <ul id="printJobMenu" class="collapse dropdown-nav">
                        <li>
                            <a href="{{ url('TransCashJob') }}"> Cash Orders </a>
                        </li>
                    </ul>
                </li>

             @endif

         </ul>
     </nav>
 </aside>
 <!-- ======== sidebar-nav end =========== -->
