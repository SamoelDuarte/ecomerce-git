@extends('user-front.layout')
@section('breadcrumb_title', $pageHeading->edit_profile_page ?? __('My Profile'))
@section('page-title', $pageHeading->edit_profile_page ?? __('My Profile'))

@section('content')

    <!-- Dashboard Start -->
    <section class="user-dashboard pt-100 pb-70">
        <div class="container">
            <div class="row gx-xl-5">
                @includeIf('user-front.customer.side-navbar')
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="user-profile-details">
                                <div class="account-info radius-md">
                                    <div class="title">
                                        <h3>{{ $keywords['My Profile'] ?? __('My Profile') }}</h3>
                                    </div>
                                    <div class="edit-info-area">
                                        <form action="{{ route('customer.update_profile', getParam()) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="upload-img">
                                                <div class="img-box">
                                                    <img id="imagePreview" class="lazyload"
                                                        src="{{ is_null($authUser->image) ? asset('assets/user-front/images/avatar-1.jpg') : asset('assets/user-front/images/users/' . $authUser->image) }}"
                                                        alt="Image">
                                                </div>
                                                <div class="file-upload-area">
                                                    <div class="upload-file">
                                                        <input type="file" name="image" class="upload"
                                                            id="imageUpload">
                                                        <span
                                                            class="btn btn-md radius-sm w-100">{{ $keywords['Upload'] ?? __('Upload') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group mb-30">
                                                        <input type="text" class="form-control" placeholder="Nome"
                                                            name="first_name" id="first_name"
                                                            value="{{ old('first_name', $authUser->first_name) }}" required>
                                                        @error('first_name')
                                                            <p class="mb-3 text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group mb-30">
                                                        <input type="text" class="form-control" placeholder="Sobrenome"
                                                            name="last_name" id="last_name"
                                                            value="{{ old('last_name', $authUser->last_name) }}" required>
                                                        @error('last_name')
                                                            <p class="mb-3 text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group mb-30">
                                                        <input type="email" class="form-control" placeholder="Email"
                                                            name="email" id="email"
                                                            value="{{ old('email', $authUser->email) }}" required>
                                                        @error('email')
                                                            <p class="mb-3 text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group mb-30">
                                                        <input type="text" class="form-control" placeholder="Telefone"
                                                            name="contact_number" id="contact_number"
                                                            value="{{ old('contact_number', $authUser->contact_number) }}"
                                                            required>
                                                        @error('contact_number')
                                                            <p class="mb-3 text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group mb-30">
                                                        <input type="text" class="form-control" placeholder="CEP"
                                                            name="billing_zip" id="billing_zip"
                                                            value="{{ old('billing_zip', $authUser->billing_zip) }}"
                                                            required>
                                                        @error('billing_zip')
                                                            <p class="mb-3 text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- Endereço detalhado separado --}}
                                                <div class="col-lg-6">
                                                    <div class="form-group mb-30">
                                                        <input type="text" class="form-control" placeholder="Rua"
                                                            name="billing_street" id="billing_street"
                                                            value="{{ old('billing_street', $authUser->billing_street) }}"
                                                            required>
                                                        @error('billing_street')
                                                            <p class="mb-3 text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-2">
                                                    <div class="form-group mb-30">
                                                        <input type="text" class="form-control" placeholder="Número"
                                                            name="billing_number_home" id="billing_number_home"
                                                            value="{{ old('billing_number_home', $authUser->billing_number_home) }}"
                                                            required>
                                                        @error('billing_number_home')
                                                            <p class="mb-3 text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-4">
                                                    <div class="form-group mb-30">
                                                        <input type="text" class="form-control" placeholder="Bairro"
                                                            name="billing_neighborhood" id="billing_neighborhood"
                                                            value="{{ old('billing_neighborhood', $authUser->billing_neighborhood) }}"
                                                            required>
                                                        @error('billing_neighborhood')
                                                            <p class="mb-3 text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group mb-30">
                                                        <input type="text" class="form-control" placeholder="Cidade"
                                                            name="billing_city" id="billing_city"
                                                            value="{{ old('billing_city', $authUser->billing_city) }}"
                                                            required>
                                                        @error('billing_city')
                                                            <p class="mb-3 text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group mb-30">
                                                        <input type="text" class="form-control" placeholder="Estado"
                                                            name="billing_state" id="billing_state"
                                                            value="{{ old('billing_state', $authUser->billing_state) }}"
                                                            required>
                                                        @error('billing_state')
                                                            <p class="mb-3 text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="form-group mb-30">
                                                        <input type="text" class="form-control"
                                                            placeholder="Referência" name="billing_reference"
                                                            id="billing_reference"
                                                            value="{{ old('billing_reference', $authUser->billing_reference) }}">
                                                        @error('billing_reference')
                                                            <p class="mb-3 text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="col-lg-12 mb-15">
                                                    <div class="form-button">
                                                        <button type="submit"
                                                            class="btn btn-md radius-sm">Enviar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- Dashboard End -->
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script src="{{ asset('assets/user/js/customer/customer.profile.edit.js') }}"></script>
