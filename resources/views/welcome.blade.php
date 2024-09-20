@extends('layouts.frontend_layout')
@section('content')
<style type="text/css">
  .product ul li{
        height: 320px;
  }
</style>
<main id="main" >
           
            
            <section >
                <div class="container" style="background: white">
                    <div class="row " >
                        <div class="col-md-12">
                            <h1>{!!$page->content!!}</h1>
                            <div hidden="" class="col-lg-offset-2 col-md-12" style="margin-bottom: 15px">
                              <div class="col-md-4">
                                <a href="{{url('bikes')}}" class="btn btn-warning  btn-block">Rentals</a>
                              </div>
                              <div class="col-md-4">
                                <a href="{{url('tours/listing')}}" class="btn btn-success btn-block">Tours</a>
                                
                              </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </section>
            <div class="client-logos"></div>
            
        </main>
@endsection
