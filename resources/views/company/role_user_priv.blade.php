@extends('layouts.template')

@section('content')
    <form action="{{ route('pref.cr.user_priv') }}" method="post">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Role for {{ $user->name }}</h3>
                <div class="card-toolbar">
                    <a href="{{ route("company.user", base64_encode($user->company_id)) }}" class="btn btn-icon btn-sm btn-success">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex flex-column align-items-center">
                    <div class="mb-5">
                        <select id="dual-listbox" multiple name="roles[]"
                            data-available-title="Role"
                            data-selected-title="Role Selected">
                            @foreach ($roles as $roleId => $roleName)
                                <option value="{{$roleId}}" {{ in_array($roleId, $priv) ? "SELECTED" : "" }} >{{ $roleName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex ">
                        @csrf
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('custom_script')
    <!-- for pinned version -->
    <link
        href="https://cdn.jsdelivr.net/npm/dual-listbox@1.0.9/dist/dual-listbox.css"
        rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/dual-listbox@1.0.9/dist/dual-listbox.min.js"></script>
    <script>
        // Class definition
        var KTDualListbox = function() {
            // Private functions
            var initDualListbox = function() {
                // Dual Listbox
                var listBoxes = $("#dual-listbox");

                var $this = listBoxes
                // get titles
                var availableTitle = ($this.attr("data-available-title") != null) ? $this.attr("data-available-title") : "Available options";
                var selectedTitle = ($this.attr("data-selected-title") != null) ? $this.attr("data-selected-title") : "Selected options";

                // get button labels
                var addLabel = ($this.attr("data-add") != null) ? $this.attr("data-add") : "Add";
                var removeLabel = ($this.attr("data-remove") != null) ? $this.attr("data-remove") : "Remove";
                var addAllLabel = ($this.attr("data-add-all") != null) ? $this.attr("data-add-all") : "Add All";
                var removeAllLabel = ($this.attr("data-remove-all") != null) ? $this.attr("data-remove-all") : "Remove All";

                // get options
                var options = [];
                $this.children("option").each(function() {
                    var value = $(this).val();
                    var label = $(this).text();
                    options.push({
                        text: label,
                        value: value
                    });
                });

                // get search option
                var search = ($this.attr("data-search") != null) ? $this.attr("data-search") : "";

                // init dual listbox
                var dualListBox = new DualListbox("#dual-listbox", {
                    addEvent: function(value) {
                        console.log(value);
                    },
                    removeEvent: function(value) {
                        console.log(value);
                    },
                    availableTitle: availableTitle,
                    selectedTitle: selectedTitle,
                    addButtonText: addLabel,
                    removeButtonText: removeLabel,
                    addAllButtonText: addAllLabel,
                    removeAllButtonText: removeAllLabel,
                });

                $(dualListBox.buttons).find(".dual-listbox__button").addClass("btn btn-sm text-dark")

                if (search == "false") {
                    dualListBox.search.classList.add("dual-listbox__search--hidden");
                }
            };

            return {
                // public functions
                init: function() {
                    initDualListbox();
                },
            };
        }();

        $(document).ready(function() {
            KTDualListbox.init();
        });
    </script>
@endsection
