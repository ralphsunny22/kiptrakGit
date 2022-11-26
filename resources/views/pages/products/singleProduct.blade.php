@extends('layouts.design')
@section('title')View Product @endsection
@section('content')

<main id="main" class="main">

    <div class="pagetitle">
      <h1>Product Information</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Product Information<li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <hr>
    <section>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            
            <div class="card-body pt-3">
              <div class="card-title clearfix">
                <div class="d-lg-flex d-grid align-items-center float-start">
                  <div>
                    <a
                    href="{{ asset('/storage/products/'.$product->image) }}"
                    data-caption="{{ isset($product->name) ? $product->name : 'no caption' }}"
                    data-fancybox
                    > 
                    <img src="{{ asset('/storage/products/'.$product->image) }}" width="100" class="img-thumbnail img-fluid"
                    alt="Photo"></a>
                  </div>
                  <div class="d-grid ms-lg-3">
                    <div class="display-6">{{ $product->name }}</div>
                    <h5>{{ $currency_symbol }}{{ $product->price }}</h5>

                    @if ($stock_available > 0)
                      <div class="d-flex justify-content-start">
                        <small class="text-success me-2">(In-Stock)</small><small>Lagos Warehouse</small>
                      </div>
                    @else
                      <small class="text-danger">(Out-Of-Stock) | Lagos Warehouse</small>
                    @endif
                    
                  </div>
                </div>
                <div class="float-lg-end">
                  <button class="btn btn-sm btn-success"><i class="bi bi-pencil-square"></i></button>
                </div>
              </div>

              <hr>

              <div class="row g-3">
                <div class="col-lg-3">
                  <label for="">SKU Code</label>
                  <div class="lead">{{ $product->code }}</div>
                </div>

                @if (isset($product->color))
                <div class="col-lg-3">
                  <label for="">Color</label>
                  <div class="lead">{{ $product->color }}</div>
                </div>
                @endif
                
                @if (isset($product->size))
                <div class="col-lg-3">
                  <label for="">Size</label>
                  <div class="lead">{{ $product->size }}</div>
                </div>
                @endif
                
                <div class="col-lg-3">
                  <label for="">Quantity</label>
                  <div class="lead">{{ $stock_available }}</div>
                </div>
                
              </div>

              <!--features-->
              @if ($features != '')
                  
                <hr>
                <div class="row g-1">

                  <div class="col-lg-12">
                    <label for="">Features</label>
                  </div>

                  @foreach ($features as $feature)
                    <div class="col-lg-4">
                      {{ $feature }}
                    </div>
                  @endforeach
                
                </div>

              @endif

            </div>
          </div>
        </div>
      </div>
    </section>

</main><!-- End #main -->

@endsection