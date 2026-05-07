<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="asic_id" class="form-label">{{ __('Asic Id') }}</label>
            <input type="text" name="asic_id" class="form-control @error('asic_id') is-invalid @enderror" value="{{ old('asic_id', $despacho?->asic_id) }}" id="asic_id" placeholder="Asic Id">
            {!! $errors->first('asic_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="modulo_id" class="form-label">{{ __('Modulo Id') }}</label>
            <input type="text" name="modulo_id" class="form-control @error('modulo_id') is-invalid @enderror" value="{{ old('modulo_id', $despacho?->modulo_id) }}" id="modulo_id" placeholder="Modulo Id">
            {!! $errors->first('modulo_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="vacuna_id" class="form-label">{{ __('Vacuna Id') }}</label>
            <input type="text" name="vacuna_id" class="form-control @error('vacuna_id') is-invalid @enderror" value="{{ old('vacuna_id', $despacho?->vacuna_id) }}" id="vacuna_id" placeholder="Vacuna Id">
            {!! $errors->first('vacuna_id', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="fecha_envio" class="form-label">{{ __('Fecha Envio') }}</label>
            <input type="text" name="fecha_envio" class="form-control @error('fecha_envio') is-invalid @enderror" value="{{ old('fecha_envio', $despacho?->fecha_envio) }}" id="fecha_envio" placeholder="Fecha Envio">
            {!! $errors->first('fecha_envio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="responsable_envio" class="form-label">{{ __('Responsable Envio') }}</label>
            <input type="text" name="responsable_envio" class="form-control @error('responsable_envio') is-invalid @enderror" value="{{ old('responsable_envio', $despacho?->responsable_envio) }}" id="responsable_envio" placeholder="Responsable Envio">
            {!! $errors->first('responsable_envio', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="cantidad" class="form-label">{{ __('Cantidad') }}</label>
            <input type="text" name="cantidad" class="form-control @error('cantidad') is-invalid @enderror" value="{{ old('cantidad', $despacho?->cantidad) }}" id="cantidad" placeholder="Cantidad">
            {!! $errors->first('cantidad', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>