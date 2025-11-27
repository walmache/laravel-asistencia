@extends('layouts.adminlte')

@section('title', __('common.create') . ' ' . __('common.events') . ' - neuroTech')
@section('page-title', '')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border border-dark mt-3">
            <div class="card-header bg-secondary bg-opacity-25 border-bottom border-dark d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0"><i class="fas fa-calendar-plus me-2"></i>{{ __('common.create') }} {{ __('common.events') }}</h3>
            </div>
            
            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" id="eventForm">
                @csrf
                <div class="card-body p-0">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs nav-fill border-bottom-0" id="eventTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-0 border-start-0" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                                <i class="fas fa-info-circle me-1"></i><span class="d-none d-md-inline">General</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-0" id="dates-tab" data-bs-toggle="tab" data-bs-target="#dates" type="button" role="tab">
                                <i class="fas fa-calendar-alt me-1"></i><span class="d-none d-md-inline">Fechas</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-0" id="pricing-tab" data-bs-toggle="tab" data-bs-target="#pricing" type="button" role="tab">
                                <i class="fas fa-dollar-sign me-1"></i><span class="d-none d-md-inline">Precios</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-0" id="location-tab" data-bs-toggle="tab" data-bs-target="#location" type="button" role="tab">
                                <i class="fas fa-map-marker-alt me-1"></i><span class="d-none d-md-inline">Ubicación</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-0 border-end-0" id="others-tab" data-bs-toggle="tab" data-bs-target="#others" type="button" role="tab">
                                <i class="fas fa-cogs me-1"></i><span class="d-none d-md-inline">Configuración</span>
                            </button>
                        </li>
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content p-4 border-top" id="eventTabsContent">
                        
                        {{-- ============================================== --}}
                        {{-- TAB 1: GENERAL --}}
                        {{-- ============================================== --}}
                        <div class="tab-pane fade show active" id="general" role="tabpanel">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Título del Evento <span class="text-danger">*</span></label>
                                    <div class="input-group border border-secondary rounded">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-heading"></i></span>
                                        <input type="text" class="form-control border-0 form-control-sm @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" placeholder="Ej: Congreso Internacional de Tecnología 2025" required>
                                    </div>
                                    @error('title')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Estado <span class="text-danger">*</span></label>
                                    <div class="input-group border border-secondary rounded">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-toggle-on"></i></span>
                                        <select class="form-select border-0 form-select-sm @error('status') is-invalid @enderror" name="status" required>
                                            <option value="borrador" {{ old('status') == 'borrador' ? 'selected' : '' }}>Borrador</option>
                                            <option value="publicado" {{ old('status') == 'publicado' ? 'selected' : '' }}>Publicado</option>
                                            <option value="cancelado" {{ old('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                            <option value="completado" {{ old('status') == 'completado' ? 'selected' : '' }}>Completado</option>
                                        </select>
                                    </div>
                                    @error('status')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Organización <span class="text-danger">*</span></label>
                                    <div class="input-group border border-secondary rounded">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-building"></i></span>
                                        <select class="form-select border-0 form-select-sm @error('organization_id') is-invalid @enderror" name="organization_id" required>
                                            <option value="">-- Seleccione una organización --</option>
                                            @foreach($organizations as $org)
                                                <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('organization_id')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Categoría</label>
                                    <div class="input-group border border-secondary rounded">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-tags"></i></span>
                                        <select class="form-select border-0 form-select-sm @error('category_id') is-invalid @enderror" name="category_id">
                                            <option value="">-- Sin categoría --</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('category_id')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Descripción Corta (Resumen)</label>
                                <div class="input-group border border-secondary rounded">
                                    <span class="input-group-text bg-light border-0 pt-2 align-items-start"><i class="fas fa-align-left"></i></span>
                                    <textarea class="form-control border-0 form-control-sm @error('short_description') is-invalid @enderror" name="short_description" rows="2" maxlength="500" placeholder="Breve descripción para listados y tarjetas de presentación (máx. 500 caracteres)">{{ old('short_description') }}</textarea>
                                </div>
                                @error('short_description')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Descripción Completa <span class="text-danger">*</span></label>
                                <div class="border border-secondary rounded p-1">
                                    <textarea class="form-control border-0 form-control-sm @error('description') is-invalid @enderror" name="description" rows="5" placeholder="Descripción detallada del evento: objetivos, público objetivo, temáticas a tratar, ponentes, etc." required>{{ old('description') }}</textarea>
                                </div>
                                @error('description')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email de Contacto <span class="text-danger">*</span></label>
                                    <div class="input-group border border-secondary rounded">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-envelope"></i></span>
                                        <input type="email" class="form-control border-0 form-control-sm @error('contact_email') is-invalid @enderror" name="contact_email" value="{{ old('contact_email') }}" placeholder="Ej: eventos@miorganizacion.com" required>
                                    </div>
                                    @error('contact_email')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Teléfono de Contacto</label>
                                    <div class="input-group border border-secondary rounded">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-phone"></i></span>
                                        <input type="text" class="form-control border-0 form-control-sm @error('contact_phone') is-invalid @enderror" name="contact_phone" value="{{ old('contact_phone') }}" placeholder="Ej: +593 99 123 4567">
                                    </div>
                                    @error('contact_phone')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            
                            <div class="row mt-2">
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_public" name="is_public" {{ old('is_public', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_public">Evento Público <small class="text-muted d-block">(Visible en listados)</small></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_open_enrollment" name="is_open_enrollment" {{ old('is_open_enrollment') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_open_enrollment">Inscripción Abierta <small class="text-muted d-block">(Cualquier usuario puede inscribirse)</small></label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="featured" name="featured" {{ old('featured') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="featured">Evento Destacado <small class="text-muted d-block">(Aparece en portada)</small></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ============================================== --}}
                        {{-- TAB 2: FECHAS --}}
                        {{-- ============================================== --}}
                        <div class="tab-pane fade" id="dates" role="tabpanel">
                            <h6 class="border-bottom pb-2 mb-3 text-secondary"><i class="fas fa-clock me-2"></i>Horario del Evento</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fecha y Hora de Inicio <span class="text-danger">*</span></label>
                                    <div class="input-group border border-secondary rounded">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-play-circle"></i></span>
                                        <input type="datetime-local" class="form-control border-0 form-control-sm @error('start_date') is-invalid @enderror" name="start_date" required value="{{ old('start_date') }}">
                                    </div>
                                    @error('start_date')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fecha y Hora de Fin <span class="text-danger">*</span></label>
                                    <div class="input-group border border-secondary rounded">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-stop-circle"></i></span>
                                        <input type="datetime-local" class="form-control border-0 form-control-sm @error('end_date') is-invalid @enderror" name="end_date" required value="{{ old('end_date') }}">
                                    </div>
                                    @error('end_date')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>

                            <h6 class="border-bottom pb-2 mb-3 mt-4 text-secondary"><i class="fas fa-user-plus me-2"></i>Periodo de Inscripciones</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Apertura de Inscripciones <span class="text-danger">*</span></label>
                                    <div class="input-group border border-secondary rounded">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-calendar-check"></i></span>
                                        <input type="datetime-local" class="form-control border-0 form-control-sm @error('registration_start') is-invalid @enderror" name="registration_start" required value="{{ old('registration_start') }}">
                                    </div>
                                    @error('registration_start')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Cierre de Inscripciones <span class="text-danger">*</span></label>
                                    <div class="input-group border border-secondary rounded">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-calendar-times"></i></span>
                                        <input type="datetime-local" class="form-control border-0 form-control-sm @error('registration_deadline') is-invalid @enderror" name="registration_deadline" required value="{{ old('registration_deadline') }}">
                                    </div>
                                    @error('registration_deadline')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Fecha Límite Early Bird</label>
                                    <div class="input-group border border-secondary rounded">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-stopwatch"></i></span>
                                        <input type="datetime-local" class="form-control border-0 form-control-sm @error('early_bird_deadline') is-invalid @enderror" name="early_bird_deadline" value="{{ old('early_bird_deadline') }}">
                                    </div>
                                    <small class="text-muted">Fecha límite para aplicar el precio promocional.</small>
                                    @error('early_bird_deadline')<small class="text-danger d-block">{{ $message }}</small>@enderror
                                </div>
                            </div>
                        </div>

                        {{-- ============================================== --}}
                        {{-- TAB 3: PRECIOS --}}
                        {{-- ============================================== --}}
                        <div class="tab-pane fade" id="pricing" role="tabpanel">
                            <div class="form-check form-switch mb-4 p-3 bg-light rounded border">
                                <input class="form-check-input ms-0 me-2" type="checkbox" id="is_free" name="is_free" {{ old('is_free') ? 'checked' : '' }} onchange="togglePricing()">
                                <label class="form-check-label fw-bold" for="is_free"><i class="fas fa-gift me-2 text-success"></i>Este evento es GRATUITO</label>
                            </div>

                            <div id="pricing-fields">
                                <h6 class="border-bottom pb-2 mb-3 text-secondary"><i class="fas fa-money-bill-wave me-2"></i>Configuración de Precios</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Moneda <span class="text-danger">*</span></label>
                                        <div class="input-group border border-secondary rounded">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-coins"></i></span>
                                            <select class="form-select border-0 form-select-sm @error('currency') is-invalid @enderror" name="currency">
                                                <option value="USD" {{ old('currency', 'USD') == 'USD' ? 'selected' : '' }}>USD - Dólar Americano</option>
                                                <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                                <option value="MXN" {{ old('currency') == 'MXN' ? 'selected' : '' }}>MXN - Peso Mexicano</option>
                                                <option value="COP" {{ old('currency') == 'COP' ? 'selected' : '' }}>COP - Peso Colombiano</option>
                                                <option value="PEN" {{ old('currency') == 'PEN' ? 'selected' : '' }}>PEN - Sol Peruano</option>
                                            </select>
                                        </div>
                                        @error('currency')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Precio Regular <span class="text-danger" id="price-required">*</span></label>
                                        <div class="input-group border border-secondary rounded">
                                            <span class="input-group-text bg-light border-0">$</span>
                                            <input type="number" step="0.01" min="0" class="form-control border-0 form-control-sm @error('price') is-invalid @enderror" name="price" id="price" value="{{ old('price') }}" placeholder="Ej: 150.00">
                                        </div>
                                        @error('price')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Precio Early Bird</label>
                                        <div class="input-group border border-secondary rounded">
                                            <span class="input-group-text bg-light border-0">$</span>
                                            <input type="number" step="0.01" min="0" class="form-control border-0 form-control-sm @error('early_bird_price') is-invalid @enderror" name="early_bird_price" value="{{ old('early_bird_price') }}" placeholder="Ej: 100.00">
                                        </div>
                                        <small class="text-muted">Precio promocional para inscripciones tempranas.</small>
                                        @error('early_bird_price')<small class="text-danger d-block">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                
                                <h6 class="border-bottom pb-2 mb-3 mt-4 text-secondary"><i class="fas fa-users me-2"></i>Descuentos Grupales</h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Precio por Persona (Grupal)</label>
                                        <div class="input-group border border-secondary rounded">
                                            <span class="input-group-text bg-light border-0">$</span>
                                            <input type="number" step="0.01" min="0" class="form-control border-0 form-control-sm @error('group_price') is-invalid @enderror" name="group_price" value="{{ old('group_price') }}" placeholder="Ej: 120.00">
                                        </div>
                                        <small class="text-muted">Precio especial cuando se inscriben en grupo.</small>
                                        @error('group_price')<small class="text-danger d-block">{{ $message }}</small>@enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Mínimo de Personas para Grupo</label>
                                        <div class="input-group border border-secondary rounded">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-user-friends"></i></span>
                                            <input type="number" min="2" class="form-control border-0 form-control-sm @error('max_group_size') is-invalid @enderror" name="max_group_size" value="{{ old('max_group_size') }}" placeholder="Ej: 5">
                                        </div>
                                        <small class="text-muted">Cantidad mínima para aplicar descuento grupal.</small>
                                        @error('max_group_size')<small class="text-danger d-block">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ============================================== --}}
                        {{-- TAB 4: UBICACIÓN --}}
                        {{-- ============================================== --}}
                        <div class="tab-pane fade" id="location" role="tabpanel">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Modalidad del Evento <span class="text-danger">*</span></label>
                                    <div class="input-group border border-secondary rounded">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-broadcast-tower"></i></span>
                                        <select class="form-select border-0 form-select-sm @error('event_type') is-invalid @enderror" name="event_type" id="event_type" onchange="toggleLocationFields()" required>
                                            <option value="presencial" {{ old('event_type', 'presencial') == 'presencial' ? 'selected' : '' }}>Presencial</option>
                                            <option value="virtual" {{ old('event_type') == 'virtual' ? 'selected' : '' }}>Virtual (100% Online)</option>
                                            <option value="hibrido" {{ old('event_type') == 'hibrido' ? 'selected' : '' }}>Híbrido (Presencial + Virtual)</option>
                                        </select>
                                    </div>
                                    @error('event_type')<small class="text-danger">{{ $message }}</small>@enderror
                                    <!-- Campo oculto para sincronizar location_type -->
                                    <input type="hidden" name="location_type" id="location_type" value="{{ old('location_type', 'presencial') }}">
                                </div>
                            </div>

                            <div id="physical-fields">
                                <h6 class="border-bottom pb-2 mb-3 text-secondary"><i class="fas fa-building me-2"></i>Ubicación Física</h6>
                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label class="form-label">Dirección Completa <span class="text-danger" id="address-required">*</span></label>
                                        <div class="input-group border border-secondary rounded">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-map-marker-alt"></i></span>
                                            <input type="text" class="form-control border-0 form-control-sm @error('physical_address') is-invalid @enderror" name="physical_address" id="physical_address" value="{{ old('physical_address') }}" placeholder="Ej: Av. Amazonas N23-45 y Colón, Edificio Centro Empresarial, Quito">
                                        </div>
                                        @error('physical_address')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Sala / Auditorio</label>
                                        <div class="input-group border border-secondary rounded">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-door-open"></i></span>
                                            <input type="text" class="form-control border-0 form-control-sm @error('room_number') is-invalid @enderror" name="room_number" value="{{ old('room_number') }}" placeholder="Ej: Salón Principal, Piso 3">
                                        </div>
                                        @error('room_number')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                            </div>

                            <div id="virtual-fields" class="d-none">
                                <h6 class="border-bottom pb-2 mb-3 text-secondary mt-3"><i class="fas fa-video me-2"></i>Configuración Virtual</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Plataforma</label>
                                        <div class="input-group border border-secondary rounded">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-laptop"></i></span>
                                            <input type="text" class="form-control border-0 form-control-sm @error('virtual_platform') is-invalid @enderror" name="virtual_platform" placeholder="Ej: Zoom, Microsoft Teams, Google Meet" value="{{ old('virtual_platform') }}">
                                        </div>
                                        @error('virtual_platform')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label class="form-label">Enlace de Acceso <span class="text-danger" id="link-required">*</span></label>
                                        <div class="input-group border border-secondary rounded">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-link"></i></span>
                                            <input type="url" class="form-control border-0 form-control-sm @error('virtual_link') is-invalid @enderror" name="virtual_link" id="virtual_link" placeholder="Ej: https://zoom.us/j/123456789" value="{{ old('virtual_link') }}">
                                        </div>
                                        @error('virtual_link')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Contraseña de Sala</label>
                                        <div class="input-group border border-secondary rounded">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-key"></i></span>
                                            <input type="text" class="form-control border-0 form-control-sm @error('virtual_password') is-invalid @enderror" name="virtual_password" value="{{ old('virtual_password') }}" placeholder="Ej: evento2025">
                                        </div>
                                        @error('virtual_password')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ============================================== --}}
                        {{-- TAB 5: CONFIGURACIÓN AVANZADA --}}
                        {{-- ============================================== --}}
                        <div class="tab-pane fade" id="others" role="tabpanel">
                            
                            <!-- Capacidad -->
                            <h6 class="border-bottom pb-2 mb-3 text-secondary"><i class="fas fa-users-cog me-2"></i>Capacidad e Inscripciones</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Capacidad Máxima (Cupos)</label>
                                    <div class="input-group border border-secondary rounded">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-chair"></i></span>
                                        <input type="number" min="1" class="form-control border-0 form-control-sm @error('capacity') is-invalid @enderror" name="capacity" value="{{ old('capacity') }}" placeholder="Ej: 100 (vacío = ilimitado)">
                                    </div>
                                    <small class="text-muted">Dejar vacío para capacidad ilimitada.</small>
                                    @error('capacity')<small class="text-danger d-block">{{ $message }}</small>@enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" id="waitlist_enabled" name="waitlist_enabled" {{ old('waitlist_enabled') ? 'checked' : '' }} onchange="toggleWaitlist()">
                                        <label class="form-check-label" for="waitlist_enabled">Habilitar Lista de Espera</label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3 d-none" id="waitlist-field">
                                    <label class="form-label">Máximo en Lista de Espera</label>
                                    <div class="input-group border border-secondary rounded">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-list-ol"></i></span>
                                        <input type="number" min="1" class="form-control border-0 form-control-sm @error('max_waitlist') is-invalid @enderror" name="max_waitlist" id="max_waitlist" value="{{ old('max_waitlist') }}" placeholder="Ej: 20">
                                    </div>
                                    @error('max_waitlist')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="requires_approval" name="requires_approval" {{ old('requires_approval') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="requires_approval">Requiere Aprobación Manual <small class="text-muted">(Las inscripciones deben ser aprobadas por un administrador)</small></label>
                                    </div>
                                </div>
                            </div>

                            <!-- Certificación -->
                            <h6 class="border-bottom pb-2 mb-3 text-secondary mt-4"><i class="fas fa-award me-2"></i>Certificación</h6>
                            <div class="form-check form-switch mb-3 p-3 bg-light rounded border">
                                <input class="form-check-input ms-0 me-2" type="checkbox" id="provides_certificate" name="provides_certificate" {{ old('provides_certificate') ? 'checked' : '' }} onchange="toggleCertificate()">
                                <label class="form-check-label fw-bold" for="provides_certificate"><i class="fas fa-certificate me-2 text-warning"></i>Este evento otorga certificado</label>
                            </div>
                            
                            <div id="certificate-fields" class="d-none">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Tipo de Certificado <span class="text-danger" id="cert-type-required">*</span></label>
                                        <div class="input-group border border-secondary rounded">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-file-certificate"></i></span>
                                            <input type="text" class="form-control border-0 form-control-sm @error('certificate_type') is-invalid @enderror" name="certificate_type" id="certificate_type" placeholder="Ej: Asistencia, Aprobación, Participación" value="{{ old('certificate_type') }}">
                                        </div>
                                        @error('certificate_type')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Horas Académicas <span class="text-danger" id="cert-hours-required">*</span></label>
                                        <div class="input-group border border-secondary rounded">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-hourglass-half"></i></span>
                                            <input type="number" min="1" class="form-control border-0 form-control-sm @error('certificate_hours') is-invalid @enderror" name="certificate_hours" id="certificate_hours" value="{{ old('certificate_hours') }}" placeholder="Ej: 40">
                                        </div>
                                        @error('certificate_hours')<small class="text-danger">{{ $message }}</small>@enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Asistencia Mínima (%) <span class="text-danger">*</span></label>
                                        <div class="input-group border border-secondary rounded">
                                            <span class="input-group-text bg-light border-0"><i class="fas fa-percentage"></i></span>
                                            <input type="number" min="0" max="100" class="form-control border-0 form-control-sm @error('min_attendance_percentage') is-invalid @enderror" name="min_attendance_percentage" value="{{ old('min_attendance_percentage', 80) }}" placeholder="Ej: 80">
                                        </div>
                                        <small class="text-muted">Porcentaje mínimo para obtener certificado.</small>
                                        @error('min_attendance_percentage')<small class="text-danger d-block">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Reconocimiento Facial -->
                            <h6 class="border-bottom pb-2 mb-3 text-secondary mt-4"><i class="fas fa-user-check me-2"></i>Control de Asistencia</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="allow_face_checkin" name="allow_face_checkin" {{ old('allow_face_checkin', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="allow_face_checkin">Permitir Check-in con Reconocimiento Facial</label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Umbral de Confianza Facial</label>
                                    <div class="input-group border border-secondary rounded">
                                        <span class="input-group-text bg-light border-0"><i class="fas fa-sliders-h"></i></span>
                                        <input type="number" step="0.01" min="0.1" max="1" class="form-control border-0 form-control-sm @error('face_threshold') is-invalid @enderror" name="face_threshold" value="{{ old('face_threshold', '0.60') }}" placeholder="Ej: 0.60">
                                    </div>
                                    <small class="text-muted">Valor entre 0.1 (menos estricto) y 1.0 (muy estricto). Recomendado: 0.60</small>
                                    @error('face_threshold')<small class="text-danger d-block">{{ $message }}</small>@enderror
                                </div>
                            </div>

                            <!-- Multimedia -->
                            <h6 class="border-bottom pb-2 mb-3 text-secondary mt-4"><i class="fas fa-images me-2"></i>Multimedia</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Imagen Principal del Evento</label>
                                    <input type="file" class="form-control form-control-sm @error('featured_image') is-invalid @enderror" name="featured_image" accept="image/jpeg,image/png,image/webp">
                                    <small class="text-muted">Formatos: JPG, PNG, WEBP. Máximo 2MB. Tamaño recomendado: 1200x630px</small>
                                    @error('featured_image')<small class="text-danger d-block">{{ $message }}</small>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Folleto / Brochure (PDF)</label>
                                    <input type="file" class="form-control form-control-sm @error('brochure_file') is-invalid @enderror" name="brochure_file" accept="application/pdf">
                                    <small class="text-muted">Formato: PDF. Máximo 5MB.</small>
                                    @error('brochure_file')<small class="text-danger d-block">{{ $message }}</small>@enderror
                                </div>
                            </div>

                            <!-- Políticas -->
                            <h6 class="border-bottom pb-2 mb-3 text-secondary mt-4"><i class="fas fa-file-contract me-2"></i>Políticas y Términos</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Política de Cancelación</label>
                                    <div class="border border-secondary rounded p-1">
                                        <textarea class="form-control border-0 form-control-sm @error('cancellation_policy') is-invalid @enderror" name="cancellation_policy" rows="3" placeholder="Ej: Cancelaciones permitidas hasta 48 horas antes del evento con reembolso del 100%. Después de ese plazo no se realizan devoluciones.">{{ old('cancellation_policy') }}</textarea>
                                    </div>
                                    @error('cancellation_policy')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Política de Reembolso</label>
                                    <div class="border border-secondary rounded p-1">
                                        <textarea class="form-control border-0 form-control-sm @error('refund_policy') is-invalid @enderror" name="refund_policy" rows="3" placeholder="Ej: Reembolso completo si se cancela con 7 días de anticipación. 50% si se cancela con 3 días de anticipación.">{{ old('refund_policy') }}</textarea>
                                    </div>
                                    @error('refund_policy')<small class="text-danger">{{ $message }}</small>@enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Términos y Condiciones</label>
                                <div class="border border-secondary rounded p-1">
                                    <textarea class="form-control border-0 form-control-sm @error('terms_conditions') is-invalid @enderror" name="terms_conditions" rows="3" placeholder="Ej: Al registrarse, el participante acepta los términos de uso, política de privacidad y autoriza el uso de su imagen para fines promocionales del evento.">{{ old('terms_conditions') }}</textarea>
                                </div>
                                @error('terms_conditions')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-secondary bg-opacity-25 border-top border-dark text-end">
                    <a href="{{ route('events.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-times me-1"></i>{{ __('common.cancel') }}</a>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i>{{ __('common.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar estado de campos condicionales
    togglePricing();
    toggleLocationFields();
    toggleWaitlist();
    toggleCertificate();
});

/**
 * Muestra/oculta campos de precios según si el evento es gratuito
 */
function togglePricing() {
    const isFree = document.getElementById('is_free').checked;
    const fields = document.getElementById('pricing-fields');
    const priceInput = document.getElementById('price');
    const priceRequired = document.getElementById('price-required');
    
    if (isFree) {
        fields.style.opacity = '0.5';
        fields.style.pointerEvents = 'none';
        priceInput.required = false;
        priceInput.value = 0;
        priceRequired.classList.add('d-none');
    } else {
        fields.style.opacity = '1';
        fields.style.pointerEvents = 'auto';
        priceInput.required = true;
        priceRequired.classList.remove('d-none');
    }
}

/**
 * Muestra/oculta campos de ubicación física o virtual según la modalidad
 */
function toggleLocationFields() {
    const type = document.getElementById('event_type').value;
    const locationInput = document.getElementById('location_type');
    const physical = document.getElementById('physical-fields');
    const virtual = document.getElementById('virtual-fields');
    const address = document.getElementById('physical_address');
    const link = document.getElementById('virtual_link');
    const addressRequired = document.getElementById('address-required');
    const linkRequired = document.getElementById('link-required');

    // Sincronizar location_type con event_type
    locationInput.value = type;

    if (type === 'presencial') {
        physical.classList.remove('d-none');
        virtual.classList.add('d-none');
        address.required = true;
        link.required = false;
        addressRequired.classList.remove('d-none');
    } else if (type === 'virtual') {
        physical.classList.add('d-none');
        virtual.classList.remove('d-none');
        address.required = false;
        link.required = true;
        addressRequired.classList.add('d-none');
        linkRequired.classList.remove('d-none');
    } else { // Híbrido
        physical.classList.remove('d-none');
        virtual.classList.remove('d-none');
        address.required = true;
        link.required = true;
        addressRequired.classList.remove('d-none');
        linkRequired.classList.remove('d-none');
    }
}

/**
 * Muestra/oculta campo de máximo en lista de espera
 */
function toggleWaitlist() {
    const enabled = document.getElementById('waitlist_enabled').checked;
    const field = document.getElementById('waitlist-field');
    const input = document.getElementById('max_waitlist');
    
    if (enabled) {
        field.classList.remove('d-none');
        input.required = true;
    } else {
        field.classList.add('d-none');
        input.required = false;
    }
}

/**
 * Muestra/oculta campos de certificación
 */
function toggleCertificate() {
    const enabled = document.getElementById('provides_certificate').checked;
    const fields = document.getElementById('certificate-fields');
    const type = document.getElementById('certificate_type');
    const hours = document.getElementById('certificate_hours');
    
    if (enabled) {
        fields.classList.remove('d-none');
        type.required = true;
        hours.required = true;
    } else {
        fields.classList.add('d-none');
        type.required = false;
        hours.required = false;
    }
}
</script>
@endpush
