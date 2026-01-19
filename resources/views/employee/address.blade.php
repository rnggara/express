@extends('layouts.template')

@section('css')
    <link href="{{ asset('assets/plugins/custom/leaflet/leaflet.bundle.css?v=7.0.5') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
@endsection

@section('content')
    <a href="{{ route("employee.detail", $emp->id) }}" class="d-flex align-items-center mb-5 text-primary">
        <i class="fa fa-chevron-left me-3 text-primary"></i>
        <span class="fw-bold">{{ $emp->emp_name }}</span>
    </a>
    <form action="{{route('emp.coordinates.save', $emp->id)}}" method="post" id="form_post">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 p-5 mb-5">
                        <div class="fv-row mb-5">
                            <label class="col-form-label" for="latitude">Latitude</label>
                            <input type="text" name="latitude" id="latitude" class="form-control w-25" required placeholder="Latitude" readonly>
                        </div>
                        <div class="fv-row mb-5">
                            <label class="col-form-label" for="longitude">Longitude</label>
                            <input type="text" name="longitude" id="longitude" class="form-control w-25" required placeholder="Longitude" readonly>
                        </div>
                        <div class="fv-row mb-5">
                            <label class="col-form-label" for="radius">Radius</label>
                            <input type="number" name="radius" id="radius" class="form-control w-25" required placeholder="Radius" value="1000">
                        </div>
                        <div class="fv-row">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>
                    <div class="col-12 border p-5">
                        <div id="kt_leaflet_6" style="height:800px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('custom_script')
    <script src="{{ asset('theme/assets/plugins/custom/leaflet/leaflet.bundle.js') }}"></script>
    <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
    <script>

        var dt

        var demo6 = function () {

            // init leaflet map
            var leaflet = new L.Map('kt_leaflet_6', {
                zoomSnap : 0.1,
                minZoom: 5.6,
            }).setView([-2.232555671751522, 117.63552256391021], 5);
            leaflet.setZoom(5.6)


            leaflet.addLayer(new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'));

            // add scale layer
            L.control.scale().addTo(leaflet);

            leaflet.on("click", onMapClick)

            var popup = L.popup();

            var leafletIcon = L.divIcon({
                    html: `<span class="svg-icon svg-icon-3x"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="24" width="24" height="0"/><path style="fill: #7340E5" d="M5,10.5 C5,6 8,3 12.5,3 C17,3 20,6.75 20,10.5 C20,12.8325623 17.8236613,16.03566 13.470984,20.1092932 C12.9154018,20.6292577 12.0585054,20.6508331 11.4774555,20.1594925 C7.15915182,16.5078313 5,13.2880005 5,10.5 Z M12.5,12 C13.8807119,12 15,10.8807119 15,9.5 C15,8.11928813 13.8807119,7 12.5,7 C11.1192881,7 10,8.11928813 10,9.5 C10,10.8807119 11.1192881,12 12.5,12 Z" fill="#000000" fill-rule="nonzero"/></g></svg></span>`,
                    bgPos: [10, 10],
                    iconAnchor: [20, 37],
                    popupAnchor: [0, -37],
                    className: 'leaflet-marker'
                });

            var marker = L.marker({icon : leafletIcon});

            @if(!empty($emp->latitude) && !empty($emp->longitude))
                var latlnginit = {
                    lat : "{{$emp->latitude}}",
                    lng : "{{$emp->longitude}}",
                }

                setLatLng(latlnginit, 20)
            @endif

            L.Control.geocoder({defaultMarkGeocode: false}).on("markgeocode", function(e){
                var geocode = e.geocode
                var center = geocode.center
                setLatLng(center)
                var bbox = e.geocode.bbox;
                var poly = L.polygon([
                    bbox.getSouthEast(),
                    bbox.getNorthEast(),
                    bbox.getNorthWest(),
                    bbox.getSouthWest()
                ]);
                leaflet.fitBounds(poly.getBounds());
            }).addTo(leaflet);

            function onMapClick(e) {
                var latln = e.latlng
                // leaflet.setView(latln, 20)
                setLatLng(e.latlng)
            }

            function setLatLng(latlng, zoom = 5){
                marker.setIcon(leafletIcon).setLatLng(latlng).addTo(leaflet).bindPopup( latlng.toString())
                leaflet.setView(latlng, 20)
                $("#latitude").val(latlng.lat)
                $("#longitude").val(latlng.lng)
            }
        }

        // Define form element
        const form = document.getElementById('form_post');

        // Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
        var validator = FormValidation.formValidation(
            form,
            {
                fields: {
                    'latitude': {
                        validators: {
                            notEmpty: {
                                message: 'Latitude is required'
                            }
                        }
                    },
                    'longitude': {
                        validators: {
                            notEmpty: {
                                message: 'Longitude is required'
                            }
                        }
                    },
                },

                plugins: {
                    trigger: new FormValidation.plugins.Trigger(),
                    bootstrap: new FormValidation.plugins.Bootstrap5({
                        rowSelector: '.fv-row',
                        eleInvalidClass: '',
                        eleValidClass: ''
                    })
                }
            }
        );

        $(document).ready(function(){
            demo6()

            $("#form_post button[type=submit]").click(function(e){
                e.preventDefault()

                validator.validate().then(function(status){
                    if(status == "Valid"){
                        $("#form_post").submit()
                    }
                })
            })

        })

    </script>
@endsection
