<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0, user-scalable=0"
            name="viewport">

        <title>Order Form :: CRM</title>
        <meta content="" name="description">
        <meta content="" name="keywords">

        <!-- Favicons -->
        <link href="{{asset('/customerform/assets/img/favicon.png')}}" rel="icon">
        <link href="{{asset('/customerform/assets/img/apple-touch-icon.png')}}" rel="apple-touch-icon">
        <!-- Google Fonts -->
        <link href="https://fonts.gstatic.com" rel="preconnect">
        <link
            href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
            rel="stylesheet">

        <!-- Vendor CSS Files -->
        <link href="{{asset('/customerform/assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('/customerform/assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
        <!-- Latest compiled and minified CSS -->
        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css')}}"> -->
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/css/bootstrap-select.min.css"
            />
        <link href="{{asset('/customerform/assets/vendor/boxicons/css/boxicons.min.css')}}"
            rel="stylesheet">
        <link href="{{asset('/customerform/assets/vendor/quill/quill.snow.css')}}" rel="stylesheet">
        <link href="{{asset('/customerform/assets/vendor/quill/quill.bubble.css')}}" rel="stylesheet">
        <link href="{{asset('/customerform/assets/vendor/remixicon/remixicon.css')}}" rel="stylesheet">
        <link href="{{asset('/customerform/assets/vendor/simple-datatables/style.css')}}" rel="stylesheet">
    
        <!-- Font awesome 5 -->
        <link rel="preload" href="{{asset('/customerform/assets/vendor/font-awesome/webfonts/fa-solid-900.woff2')}}" as="font" type="font/woff" crossorigin>
        <link href="{{asset('/customerform/assets/vendor/font-awesome/css/all.min.css')}}" type="text/css" rel="stylesheet">

        
        <!-- Template Main CSS File -->
        <link href="{{asset('/customerform/assets/css/ui.css')}}" rel="stylesheet">
        <link href="{{asset('/customerform/assets/css/form-style.css')}}" rel="stylesheet">

        
    </head>

    <body class="">

        <!-- will be shown in singlelink-->
    <nav class="navbar bg-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
            <img src="{{asset('/customerform/assets/img/logo.png')}}" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
            <span class="project-name"></span>
            </a>
        </div>
    </nav>

    <main class="container mb-5 py-5 min-vh-100">
        
        @if(Session::has('success'))
        <div class="alert alert-success mb-3 text-center">
            {{Session::get('success')}}
        </div>
        @endif

        @if(Session::has('info'))
        <div class="alert alert-success mb-3 text-center">
            {{Session::get('info')}}
        </div>
        @endif

            <!-- Monitoring diferent stages in the form -->
            <input type="hidden" name="main_stage" class="main_stage" value="">
            <input type="hidden" name="orderbump_stage" class="orderbump_stage" value="">
            <input type="hidden" name="upsell_stage" class="upsell_stage" value="">
            <input type="hidden" name="thankyou_stage" class="thankyou_stage" value="">
            <!-- Monitoring diferent stages in the form -->
        
            <!-- CHECKOUT VIEW Main + orderbump + upsell -->
            @if ($stage == "")  
        
            <div class="row view" id="main-section" style="display: block;">
                <div class="col-md-12">

                    <article class="card">
                        <div class="card-body">
                            <h5 class="card-title">Contact info</h5>
                            {{-- <form action="{{ route('newFormLinkPost', $unique_key) }}" method="POST">@csrf --}}
                            <form action="">@csrf
                                <input type="hidden" name="formholder_unique_key" class="formholder_unique_key" value="{{ $unique_key }}">
                                <div class="row">

                                    @foreach ($formContact as $contact)
                                    @if (($contact['form_name']) !== 'Product Package')
                                    <div class="col-lg-6 mb-3">
                                        <label class="form-label">{{ $contact['form_label'] }}</label>
                                        @if($contact['form_type'] == 'text_field')
                                        <input type="{{ $contact['form_name'] == 'active-email' ? 'email' : 'text' }}" class="form-control {{ $contact['form_name'] }} @error($contact['form_name']) is-invalid @enderror"
                                        placeholder="Type here" required>
                                        @endif
                                        <!--if such error-->
                                        @error($contact['form_name'])
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    
                                    </div> <!-- col end.// -->
                                    @endif
                                    
                                    @endforeach
                                    
                                    <hr>
                                    <div class="col-12 mb-1 mt-3 fw-bolder">Select A Package From Below</div>
                                    
                                    @foreach ($products as $key=>$item)
                                    <div class="col-12 mb-3">
                                        <label for="package{{$key}}" class="form-label btn btn-outline border d-flex align-items-center me-3 @error($item['form_name']) is-invalid @enderror">
                                            <input type="{{ $item['form_type'] == 'package_single' ? 'radio' : 'checkbox' }}" name="product_packages[]" id="package{{$key}}"
                                            class="contact-check me-3 product-package" value="{{ $item['id'] }}"/>
                                            <span class="me-1 fw-bold">{{ $item['name'] }} = {{ $item['price'] }} naira</span>
                                        </label>
                                        @error($item['form_name'])
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div> <!-- col end.// -->
                                    @endforeach

                                    <div class="col-12">
                                        <div class="d-flex justify-content-center">
                                            <button type="button" class="btn w-50 p-2 text-white main_package_submit_btn" style="background-color: #012970">Submit Order</button>
                                        </div>
                                    </div>
                                        
                                </div> <!-- row.// --> 
                                
                            </form>
                        </div> <!-- card-body end.// -->
                    </article>

                </div>
                
            </div>

            @endif

            <!---orderbump view--->
            <input type="hidden" name="has_orderbump" class="has_orderbump" value="{{ isset($formHolder->orderbump_id) ? 'true' : 'false' }}">
            @if ($stage == "")
            @if (isset($formHolder->orderbump_id))
                <div class="row view" id="orderbump-section" style="display: none;">
                
                    <div class="col-md-12">
        
                        <article class="card">
                            <div class="card-body">
                                {{-- <h5 class="card-title">Contact info</h5> --}}
                                <form action="">@csrf
                                    <div class="row">
        
                                        <!--upsell-->
                                        <div class="col-12 mb-3">
                                            <div class="d-flex justify-content-center">
                                                <div class="content text-center p-3" style="border: 3px dashed black; background-color: #D2FFE8;">
                                                    <h3 class="heading">{{ $formHolder->orderbump->orderbump_heading }}</h3>
                                                    <h4 class="subheading" style="color: #012970;">{{ $formHolder->orderbump->orderbump_subheading }}</h4>
                                                    {{-- <p class="product-feature">Melts Away Fats In 2 Days!</p> --}}
    
                                                    <div class="orderbump-product-image mb-3">
                                                        <img src="{{ asset('/storage/products/'.$formHolder->orderbump->product->image) }}" width="100" class="img-thumbnail img-fluid"
                                                        alt="{{ $formHolder->orderbump->product->name }}">
                                                    </div>
    
                                                    <p class="discount-info">
                                                        Kindly click the box below to add this to your order now for just xxxx instead of paying normal price of yyyy!
                                                    </p>
    
                                                    <p class="more-info">
                                                        This offer is not available at ANY other time or place
                                                    </p>

                                                    <div class="col-12 mb-3 select-product">
                                                        <p class="form-label">Click button to Add Product Package</p>
                                                        <label for="product" class="form-label btn btn-outline border d-flex align-items-center me-3
                                                        @error('product') is-invalid @enderror">
                                                            <input type="hidden" name="orderbump_product_checkbox" id="product"
                                                            class="orderbump_product_checkbox me-3" value="{{ $formHolder->orderbump->product->id }}"/>
                                                            <span class="me-1 fw-bold">{{ $formHolder->orderbump->product->name }} =
                                                                {{ $formHolder->orderbump->product->country->symbol }}{{ $formHolder->orderbump->product->price }}</span>
                                                        </label>
                                                        @error('product')
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $message }}</strong>
                                                            </span>
                                                        @enderror
                                                    </div>
    
                                                    <div class="make-your-choice d-flex justify-content-center">
    
                                                        <label for="orderbump_refusal" class="form-label d-flex align-items-center">
                                                            <input type="checkbox" name="orderbump_refusal" id="orderbump_refusal" class="cta-check2 me-1 orderbump_refusal"
                                                            @error('product') checked @enderror value="true"/>
                                                            <span class="fw-bold" style="color: #012970;">No, thank you</span>
                                                        </label>
    
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" class="btn w-50 p-2 text-white orderbump_submit_btn" style="background-color: #012970;">ADD TO MY ORDER</button>
                                            </div>
                                        </div>
                                            
                                    </div> <!-- row.// --> 
                                    
                                </form>
                            </div> <!-- card-body end.// -->
                        </article>
        
                    </div>
                    
                </div>
            @endif
            @endif

            <!---upsell view--->
            <input type="hidden" name="has_upsell" class="has_upsell" value="{{ isset($formHolder->upsell_id) ? 'true' : 'false' }}">
            @if ($stage == "")  
            @if (isset($formHolder->upsell_id))
                <div class="row view" id="upsell-section" style="display: none;">
                
                    <div class="col-md-12">
        
                        <article class="card">
                            <div class="card-body">
                                {{-- <h5 class="card-title">Contact info</h5> --}}
                                <form action="">@csrf
                                    <div class="row">
        
                                        <!--upsell-->
                                        <div class="col-12 mb-3">
                                            <div class="d-flex justify-content-center">
                                                <div class="content text-center p-3">
                                                    <h3 class="heading">{{ $formHolder->upsell->upsell_heading }}</h3>
                                                    <h4 class="subheading" style="color: #012970;">{{ $formHolder->upsell->upsell_subheading }}</h4>
                                                    
                                                    <div class="upsell-product-image mb-3">
                                                        <img src="{{ asset('/storage/products/'.$formHolder->upsell->product->image) }}" width="100" class="img-thumbnail img-fluid"
                                                        alt="{{$formHolder->upsell->product->name}}">
                                                    </div>
        
                                                    <div class="select-upsell-product text-center">
                                                        <p class="form-label">Click button to Add Product Package</p>
                                                        <div class="call-to-action d-flex justify-content-center align-items-center">
                                                            <label for="upsell_product" class="form-label d-flex align-items-center">
                                                                <input type="hidden" name="upsell_product" class="upsell_product_checkbox me-1" id="upsell_product"
                                                                value="{{ $formHolder->upsell->product->id }}">
                                                                <span>{{ $formHolder->upsell->product->name }} =
                                                                    {{ $formHolder->upsell->product->country->symbol }}{{ $formHolder->upsell->product->price }}</span>
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="make-your-choice d-flex justify-content-center">
    
                                                        <label for="upsell_refusal" class="form-label d-flex align-items-center">
                                                            <input type="checkbox" name="upsell_refusal" id="upsell_refusal" class="cta-check2 me-1 upsell_refusal"
                                                            @error('product') checked @enderror value="true"/>
                                                            <span class="fw-bold" style="color: #012970;">No, thank you</span>
                                                        </label>
    
                                                    </div>
                                                    
                                                        
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="d-flex justify-content-center">
                                                <button type="submit" class="btn w-50 p-2 text-white upsell_submit_btn" style="background-color: #012970;">ADD TO MY ORDER</button>
                                            </div>
                                        </div>
                                            
                                    </div> <!-- row.// --> 
                                    
                                </form>
                            </div> <!-- card-body end.// -->
                        </article>
        
                    </div>
                    
                </div>
            @endif
            @endif

        
        
            <!-- THANKYOU VIEW -->
            @if ($stage != "") 
            <div class="view" id="thankyou-section" style="display: block;">
                <div class="row">
                    <div class="col-lg-12 mb-3">
                        <article class="card shadow-sm">
                            <div class="card-body"> 
                                <div class="mt-4 mx-auto text-center" style="max-width:600px">
                                    <svg width="96px" height="96px" viewBox="0 0 96 96" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"> <g id="round-check"> <circle id="Oval" fill="#D3FFD9" cx="48" cy="48" r="48"></circle> <circle id="Oval-Copy" fill="#87FF96" cx="48" cy="48" r="36"></circle> <polyline id="Line" stroke="#04B800" stroke-width="4" stroke-linecap="round" points="34.188562 49.6867496 44 59.3734993 63.1968462 40.3594229"></polyline> </g> </g> </svg> 
                                    <div class="my-3"> 
                                        <h4>Thank you for order</h4> 
                                        <p>We have received your order confirmation. One of our agents will contact you shortly.</p> 
                                    </div>
                                </div>                 
                            </div>
                        </article>
                    </div>
                    
                    <!--order-summary-->
                    @if ($customer !== '')
                        
                    
                    <div class="col-lg-12">
                        <article class="card shadow-sm mb-3">
                            <div class="card-body">
                                <header class="d-md-flex">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0"> Order ID: {{ $orderId }} <i class="dot"></i><span class="text-danger"> Pending </span> </h6> 
                                        <span>Date: <span class="order_updated_date">{{ $order->updated_at->format('D, jS M Y') }}</span></span>
                                    </div> 
                                    <div> 
                                        <!-- <a href="#" class="btn btn-sm btn-outline-danger">Cancel order</a> -->
                                        <a href="#" id="generate-pdf" class="btn btn-sm btn-success"><i class="bi bi-download text-white"></i> Download Invoice</a> 
                                    </div> 
                                </header> 
                                <hr> 
                                <div class="row"> 
                                    <div class="col-md-4"> 
                                        <p class="fw-bold mb-0 text-success">Contact</p> 
                                        <hr>
                                        <p class="m-0"> 
                                            <span class="customer_name">Name: {{ $customer->firstname }} {{ $customer->lastname }}</span> 
                                            <br> Phone: <span class="customer_phone">{{ $customer->phone_number }}, {{ $customer->whatsapp_phone_number }}</span> 
                                            <br> Email: <span class="customer_email">{{ $customer->email }}</span> 
                                        </p>
                                    </div> <!-- col.// --> 
                                    <div class="col-md-4 border-start"> 
                                        <p class="fw-bold mb-0 text-success">Shipping address</p> 
                                        <hr>
                                        <p class="m-0"> <span class="customer_country"></span> 
                                            <br> <span class="customer_address">{{ $customer->delivery_address }}</span> 
                                        </p>
                                    </div> <!-- col.// --> 
                                    <div class="col-md-4 border-start">
                                        <p class="fw-bold mb-0 text-success">Payment</p> 
                                        <hr>
                                        <p class="m-0">
                                            <!-- <span class="text-success"> Cash Payment </span>  -->
                                            <dl class="dlist-align">
                                                <dt class="fw-bolder">Method:</dt> <dd>Cash Payment</dd>
                                            </dl>
                                            <dl class="dlist-align">
                                                <dt class="fw-bolder">No. of Packages:</dt> <dd><span class="no_of_items">{{ $qty_total }}</span></dd>
                                            </dl>
                                            <dl class="dlist-align">
                                                <dt class="fw-bolder">Order Amount:</dt> <dd>N<span class="order_amount">{{ $order_total_amount }}</span></dd> 
                                            </dl> 
                                            <dl class="dlist-align">
                                                <dt class="fw-bolder">Discount:</dt> <dd>N0.00</dd> 
                                            </dl> 
                                            <dl class="dlist-align">
                                                <dt class="fw-bolder">Grand Total:</dt> <dd>N<span class="grand_total">{{ $grand_total }}</span></dd> 
                                            </dl> 
                                        </p>
                                    </div> <!-- col.// --> 
                                </div> <!-- row.// --> 
                                <hr>
                                <ul class="row g-3">
                                    <div class="text-center"><p class="fw-bold mb-0 text-success">Products you ordered</p> <hr></div>
                                    @foreach ($mainProducts_outgoingStocks as $main_outgoingStock)
                                    <li class="col-lg-4 col-md-6"> 
                                        <div class="itemside mb-3"> 
                                            <div class="aside"> 
                                                <img width="72" height="72" src="{{ asset('/storage/products/'.$main_outgoingStock->product->image) }}" class="img-sm rounded border">
                                            </div> 
                                            <div class="info"> 
                                                <p class="title">{{ $main_outgoingStock->product->name }}</p> 
                                                <strong>N{{ $mainProduct_revenue }} ({{ $main_outgoingStock->quantity_removed }} items)</strong> 
                                            </div> 
                                        </div> 
                                    </li>
                                    @endforeach
                                    
                                    <!---for orderbump or upsell--->
                                    @if ($orderbumpProduct_revenue !== 0)
                                    <li class="col-lg-4 col-md-6"> 
                                        <div class="itemside mb-3"> 
                                            <div class="aside"> 
                                                <img width="72" height="72" src="{{ asset('/storage/products/'.$orderbump_outgoingStock->product->image) }}" class="img-sm rounded border"> 
                                            </div> 
                                            <div class="info"> 
                                                <p class="title">{{ $orderbump_outgoingStock->product->name }}</p> 
                                                <strong>N{{ $orderbump_outgoingStock->product->price * $orderbump_outgoingStock->quantity_removed }} ({{ $orderbump_outgoingStock->quantity_removed }} item)</strong> 
                                            </div> 
                                        </div> 
                                    </li>
                                    @endif
                                    
                                    @if ($upsellProduct_revenue !== 0)
                                    <li class="col-lg-4 col-md-6"> 
                                        <div class="itemside mb-3"> 
                                            <div class="aside"> 
                                                <img width="72" height="72" src="{{ asset('/storage/products/'.$upsell_outgoingStock->product->image) }}" class="img-sm rounded border"> 
                                            </div> 
                                            <div class="info"> 
                                                <p class="title">{{ $upsell_outgoingStock->product->name }}</p> 
                                                <strong>N{{ $upsell_outgoingStock->product->price * $upsell_outgoingStock->quantity_removed }} ({{ $upsell_outgoingStock->quantity_removed }} item)</strong> 
                                            </div> 
                                        </div> 
                                    </li>
                                    @endif
                                    
                                </ul> 
                            </div> <!-- card-body .// --> 
                        </article> <!-- card .// -->                 
                    </div>

                    @endif
                    
                    
                    <!--/order-summary-->
                </div>
                
            </div>
            @endif
        <!--/ THANKYOU VIEW -->
        <div id="pdf-content" style="display: none"></div>
        <div id="pdf-renderer"></div>
        
    </main>

    <!-- <hr> will be shown in singlelink-->
    <footer class="container-fluid position-relative bg-dark py-5 text-white bottom-0" style="position: relative; bottom: 0;">
        <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div>&copy; <span class="copyright-date"></span> <span class="project-name"></span>. All rights reserved. </div>
            </div>
        </div>
        </div>
    </footer>

        <!-- Vendor JS Files -->
        <script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js')}}"></script> -->
        <script src="{{asset('/customerform/assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
        <script src="{{asset('/customerform/assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('/customerform/assets/vendor/chart.js/chart.min.js')}}"></script>
        <script src="{{asset('/customerform/assets/vendor/echarts/echarts.min.js')}}"></script>
        <script src="{{asset('/customerform/assets/vendor/quill/quill.min.js')}}"></script>
        <script src="{{asset('/customerform/assets/vendor/simple-datatables/simple-datatables.js')}}"></script>
        <script src="{{asset('/customerform/assets/vendor/tinymce/tinymce.min.js')}}"></script>
        <script src="{{asset('/customerform/assets/vendor/php-email-form/validate.js')}}"></script>


        <!-- Latest compiled and minified JavaScript -->
        <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js')}}"></script> -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.18/js/bootstrap-select.min.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>
        <!-- Template Main JS File -->
        <script src="{{asset('/customerform/assets/js/main.js?v=42')}}"></script>
        <script src="{{asset('/customerform/assets/js/navigation.js?v=4')}}"></script>

        <!-- submit main form -->
        
        @if ($stage == "")
        <script>
            var main_stage = localStorage.hasOwnProperty('main_stage') ? localStorage.getItem('main_stage') : '';
            var orderbump_stage = localStorage.hasOwnProperty('orderbump_stage') ? localStorage.getItem('orderbump_stage') : '';
            var upsell_stage = localStorage.hasOwnProperty('upsell_stage') ? localStorage.getItem('upsell_stage') : '';
            var thankyou_stage = localStorage.hasOwnProperty('thankyou_stage') ? localStorage.getItem('thankyou_stage') : '';
              $("#thankyou-section").show()
            // }

            /** SET VIEWS */
            let currentView; //initialise currentView
            const setView = (viewId) => {
                var views = document.querySelectorAll('.view');
                        
                for (let i = 0; i < views.length; i++) {
                    const view = views[i];
                    
                    view.style.display = 'none';            
                }
                
                document.getElementById(viewId).style.display = 'block';

                currentView = viewId;
            }
            //initialise view
            setView('main-section');
            /** END SET VIEWS */
            
            
            $('.main_package_submit_btn').click(function (e) {
                e.preventDefault();

                var firstname = $(".first-name").val();
                var lastname = $(".last-name").val();
                var phone_number = $(".phone-number").val();
                var whatsapp_phone_number = $(".whatsapp-phone-number").val();
                var active_email = $(".active-email").val();
                var state = $(".state").val();
                var city = $(".city").val();
                var address = $(".address").val();
                var product_package = $(".product-package").val();

                if (firstname == "" || firstname == null) {
                    alert("First name must be filled");
                    return false;
                }   
                if (lastname == "" || lastname == null) {
                    alert("Last name must be filled");
                    return false;
                }
                if (phone_number == "" || phone_number == null) {
                    alert("Phone number must be filled");
                    return false;
                }
                if (whatsapp_phone_number == "" || whatsapp_phone_number == null) {
                    alert("Phone number must be filled");
                    return false;
                }
                if (active_email == "" || active_email == null) {
                    alert("Email address must be filled");
                    return false;
                }
                if (state == "" || state == null) {
                    alert("Your State must be filled");
                    return false;
                }
                if (city == "" || city == null) {
                    alert("City or Town must be filled");
                    return false;
                }
                if (address == "" || address == null) {
                    alert("Your Address must be selected");
                    return false;
                }
                if (product_package == "" || product_package == null) {
                    alert("Your Product Package must be filled");
                    return false;
                }

                var unique_key = $(".formholder_unique_key").val();
                var product_packages = $('input[name^="product_packages[]"]').map(function () {
                    if ($(this).is(':checked')) {
                        return $(this).val();
                    }
                }).get();

                var has_orderbump = $(".has_orderbump").val();
                var has_upsell = $(".has_upsell").val();

                //ajax start
                $.ajax({
                    type:'get',
                    url:'/ajax-save-new-form-link',
                    data:{unique_key:unique_key, firstname:firstname, lastname:lastname, phone_number:phone_number, whatsapp_phone_number:whatsapp_phone_number,
                        active_email:active_email, state:state, city:city, address:address, product_packages:product_packages, 
                        },
                    success:function(resp){
                        console.log(resp)
                        $(".main_stage").val('done')
                        localStorage.setItem('main_stage', 'done');
                        if (resp.data.has_orderbump) {
                            setView('orderbump-section')
                            
                        } else if (resp.data.has_upsell) {
                            setView('upsell-section')
                            
                        } else {
                            window.location.href = "/new-form-link/"+unique_key+"/thankYou"
                            setView('thankyou-section')
                        }

    
                    },error:function(){
                        alert("Error");
                    }
                });

                //ajax end


            })

            //orderbump_stage
            $('.orderbump_submit_btn').click(function(e){
                e.preventDefault();
                var unique_key = $(".formholder_unique_key").val();
                var orderbump_product_checkbox = ''
                if ($('.orderbump_product_checkbox').val() != '') {
                    var orderbump_product_checkbox = $('.orderbump_product_checkbox').val();

                    $.ajax({
                        type:'get',
                        url:'/ajax-save-new-form-link-orderbump',
                        data:{ unique_key:unique_key, orderbump_product_checkbox:orderbump_product_checkbox },
                        success:function(resp){
                            console.log(resp)
                            localStorage.setItem('orderbump_stage', 'done');
                            if (resp.data.has_upsell) {
                                setView('upsell-section')
                                
                            } else {
                            window.location.href = "/new-form-link/"+unique_key+"/thankYou"
                            setView('thankyou-section')
                        
                            }
                                
                        },error:function(){
                            alert("Error");
                        }
                    });
                
                } else {
                    alert('Error: Something went wrong')
                }
            });

            //upsell_stage
            $('.upsell_submit_btn').click(function(e){
                e.preventDefault();
                var unique_key = $(".formholder_unique_key").val();
                var upsell_product_checkbox = ''
                if ($('.upsell_product_checkbox').val() != '') {
                    var upsell_product_checkbox = $('.upsell_product_checkbox').val();

                    $.ajax({
                        type:'get',
                        url:'/ajax-save-new-form-link-upsell',
                        data:{ unique_key:unique_key, upsell_product_checkbox:upsell_product_checkbox },
                        success:function(resp){
                            console.log(resp)
                            localStorage.setItem('upsell_stage', 'done');
                            window.location.href = "/new-form-link/"+unique_key+"/thankYou"
                            setView('thankyou-section')
                                
                        },error:function(){
                            alert("Error");
                        }
                    });
                
                } else {
                    alert('Error: Something went wrong')
                }
            });

            //orderbump_refusal
            $('.orderbump_refusal').click(function(){
                if ($(this).is(':checked')) {
                    
                    var unique_key = $(".formholder_unique_key").val();
                    var orderbump_product_checkbox = ''
                    if ($('.orderbump_product_checkbox').val() != '') {
                        var orderbump_product_checkbox = $('.orderbump_product_checkbox').val();

                        $.ajax({
                            type:'get',
                            url:'/ajax-save-new-form-link-orderbump-refusal',
                            data:{ unique_key:unique_key, orderbump_product_checkbox:orderbump_product_checkbox },
                            success:function(resp){
                                console.log(resp)
                                localStorage.setItem('orderbump_stage', 'done');
                                if (resp.data.has_upsell) {
                                    setView('upsell-section')
                                    
                                } else {
                                    window.location.href = "/new-form-link/"+unique_key+"/thankYou"
                                    setView('thankyou-section')
                            
                                }
                                    
                            },error:function(){
                                alert("Error");
                            }
                        });
                    
                    } else {
                        alert('Error: Something went wrong')
                    }

                } 
                
            });

            //upsell_refusal
            $('.upsell_refusal').click(function(){
                if ($(this).is(':checked')) {
                    
                    var unique_key = $(".formholder_unique_key").val();
                    var upsell_product_checkbox = ''
                    if ($('.upsell_product_checkbox').val() != '') {
                        var upsell_product_checkbox = $('.upsell_product_checkbox').val();

                        $.ajax({
                            type:'get',
                            url:'/ajax-save-new-form-link-upsell-refusal',
                            data:{ unique_key:unique_key, upsell_product_checkbox:upsell_product_checkbox },
                            success:function(resp){
                                console.log(resp)
                                localStorage.setItem('upsell_stage', 'done');
                                    // location.reload()
                                    window.location.href = "/new-form-link/"+unique_key+"/thankYou"
                                    setView('thankyou-section')
                                    
                            },error:function(){
                                alert("Error");
                            }
                        });
                    
                    } else {
                        alert('Error: Something went wrong')
                    }

                } 
                
            });
        </script>
        @endif

        
        <script>

        </script>

        <script>
            //not in use
            $(".cta-check").click(function(){
                if($(this).is(':checked')){
                    $(".select-product").show();
                } else {
                    $(".select-product").hide();
                }
                
            });

            
        </script>

        <script>
            $(document).on("input", ".phone-number", function() {
                this.value = this.value.replace(/\D/g,'');
            });
        </script>

        <script>
            $(document).on("input", ".whatsapp-phone-number", function() {
                this.value = this.value.replace(/\D/g,'');
            });
        </script>

        <script>
            var doc = new jsPDF();
            var specialElementHandlers = {
                '#pdf-renderer': function (element, renderer) {
                    return true;
                }
            };

            $('#generate-pdf').click(function () {   
                $('#pdf-content').show();
                doc.fromHTML($('#pdf-content').html(), 15, 15, {
                    'width': 170,
                        'elementHandlers': specialElementHandlers
                });
                doc.save('sample-file.pdf');
                $('#pdf-content').hide();
            });

        </script>

        
    </body>

</html>