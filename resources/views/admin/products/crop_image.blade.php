@extends('layouts.admin')

@section('title', __('Recortar imagen') . ' — ' . config('app.name'))

@section('page_header')
    <h1 class="text-2xl font-bold text-slate-900">{{ __('Imagen del producto') }}</h1>
@endsection

@section('content')
    <div class="card-tw max-w-6xl">
        <div class="card-tw-body">
            <div class="grid gap-8 lg:grid-cols-3">
                <div class="flex justify-center">
                    <div id="upload-demo" class="mx-auto"></div>
                </div>
                <div class="flex flex-col justify-center space-y-4">
                    <label class="label-tw">{{ __('Selecionar imagen') }}:</label>
                    <input type="file" id="image_file" class="input-tw w-full" accept="image/*">
                    <button type="button" class="btn-primary upload-image w-full">{{ __('Guardar Imagen') }}</button>
                    <div class="hidden rounded-lg border border-green-200 bg-green-50 px-4 py-2 text-sm text-green-800" id="upload-success"></div>
                </div>
                <div class="flex justify-center">
                    <div id="preview-crop-image" class="flex min-h-[300px] w-full max-w-[300px] items-center justify-center bg-slate-400 p-8"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.2/croppie.js"></script>
    <script>
        jQuery(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        var resize = jQuery('#upload-demo').croppie({
            enableExif: true,
            enableOrientation: true,
            viewport: { width: 200, height: 200, type: 'circle' },
            boundary: { width: 300, height: 300 }
        });
        jQuery('#image_file').on('change', function () {
            var reader = new FileReader();
            reader.onload = function (e) {
                resize.croppie('bind', { url: e.target.result }).then(function () {});
            };
            reader.readAsDataURL(this.files[0]);
        });
        jQuery('.upload-image').on('click', function () {
            resize.croppie('result', { type: 'canvas', size: 'viewport' }).then(function (img) {
                jQuery('#preview-crop-image').html('<img src="' + img + '" alt="" />');
                jQuery.ajax({
                    url: "/crop-image",
                    type: "POST",
                    data: {"image": img, "productID": "{{ $product->id }}"},
                    success: function () {
                        jQuery("#upload-success").html("Images cropped and uploaded successfully.").removeClass('hidden');
                        window.location.href = "/products/{{ $product->id }}/edit";
                    }
                });
            });
        });
        resize.croppie('bind', { url: 'data:image/png;base64,{{ $product->image }}' });
    </script>
@endpush
