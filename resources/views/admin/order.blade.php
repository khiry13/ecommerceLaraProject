<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    @include('admin.css')

    <style type="text/css">
        .div_center {
            text-align: center;
            padding-top: 40px;
        }

        .h2_font {
            font-size: 40px;
            padding-bottom: 40px;
        }

        .input_color {
            color: black;
        }
    </style>
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_sidebar.html -->
        @include('admin.sidebar')
        <!-- partial -->
        @include('admin.header')
        <!-- partial -->
        <div class="main-panel">
            <div class="content-wrapper">
                @if (session()->has('message'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                        {{ session()->get('message') }}
                    </div>
                @endif
                <div style="padding-top: 30px; padding-left: 400px">
                    <form action="{{ url('search') }}" method="GET">
                        @csrf
                        <input type="text" name="search" style="color: black" placeholder="Search for something">
                        <input type="submit" value="Search" class="btn btn-outline-primary">
                    </form>
                </div>
                <div style="padding-top: 50px; width: 100%; margin: auto">
                    <table class="table table-striped" style="background-color: white">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Address</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Product Title</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Price</th>
                                <th scope="col">Payment Status</th>
                                <th scope="col">Delivery Status</th>
                                <th scope="col">Image</th>
                                <th scope="col">Deliverd</th>
                                <th scope="col">Print PDF</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <th scope="row">{{ $order->name }}</th>
                                    <td>{{ $order->email }}</td>
                                    <td>{{ $order->address }}</td>
                                    <td>{{ $order->phone }}</td>
                                    <td>{{ $order->product_title }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>{{ $order->price }}</td>
                                    <td>{{ $order->payment_status }}</td>
                                    <td>{{ $order->delivery_status }}</td>
                                    <td>
                                        <img src="product/{{ $order->image }}" alt="">
                                    </td>
                                    <td>
                                        @if ($order->delivery_status == 'processing')
                                            <a href="{{ url('deliverd', $order->id) }}"
                                                onclick="return confirm('Are you sure this order is delived?')"
                                                class="btn btn-primary">deliverd</a>
                                        @else
                                            <p style="color: green">Deliverd</p>
                                        @endif
                                    </td>
                                    <td><a href="{{ url('print_pdf', $order->id) }}" class="btn btn-secondary">Print
                                            PDF</a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- container-scroller -->
        <!-- plugins:js -->
        @include('admin.script')
</body>

</html>
