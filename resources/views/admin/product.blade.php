<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    @include('admin.css')
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
                <div class="page-header">
                    <h3 class="page-title">Add Product</h3>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                @if (session()->has('message'))
                                    <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert">
                                            x
                                        </button>
                                        {{ session()->get('message') }}
                                    </div>
                                @endif
                                <form class="forms-sample" action="{{ url('add_product') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="exampleInputName1">Product Title</label>
                                        <input type="text" class="form-control" style="color: black"
                                            id="exampleInputName1" name="title" placeholder="Write the title" />
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPhone3">Product Description</label>
                                        <input type="text" class="form-control" style="color: black"
                                            id="exampleInputPhone3" name="description"
                                            placeholder="Write the description" />
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPhone3">Product Price</label>
                                        <input type="number" class="form-control" style="color: black"
                                            id="exampleInputPhone3" name="price" placeholder="Write the price" />
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPhone3">Discount Price</label>
                                        <input type="number" class="form-control" style="color: black"
                                            id="exampleInputPhone3" name="discount" placeholder="Write the discount" />
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputPhone3">Product Quantity</label>
                                        <input type="number" class="form-control" style="color: black"
                                            id="exampleInputPhone3" name="quantity" placeholder="Write the quantity" />
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleSelectGender">Product Category</label>
                                        <select style="color: white" class="form-control" name="category"
                                            id="exampleSelectGender">
                                            <option>--Select--</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->category_name }}">
                                                    {{ $category->category_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Product Image</label>
                                        <input type="file" name="image" />
                                    </div>
                                    <button type="submit" class="btn btn-primary me-2">
                                        Submit
                                    </button>
                                    <button class="btn btn-dark">
                                        Cancel
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- container-scroller -->
        <!-- plugins:js -->
        @include('admin.script')
</body>

</html>
