<div class="row form-group{{ $errors->has('barcode') ? ' has-error' : '' }}">
  <label for="barcode" class="col-md-1 form-label">Barcode</label>
  <div class="col-md-4">
    <input id="barcode" type="text" class="form-control" name="barcode" value="{{ $item->barcode or old('barcode') }}" autofocus>
    @if ($errors->has('barcode'))
      <span class="help-block">{{ $errors->first('barcode') }}</span>
    @endif
  </div>
</div>

<div class="row form-group{{ $errors->has('name') ? ' has-error' : '' }}">
  <label for="name" class="col-md-1 form-label">Name</label>
  <div class="col-md-4">
    <input id="name" type="text" class="form-control" name="name" value="{{ $item->name or old('name') }}" required autofocus>
    @if ($errors->has('name'))
      <span class="help-block">{{ $errors->first('name') }}</span>
    @endif
  </div>
</div>


<div class="row form-group{{ $errors->has('description') ? ' has-error' : '' }}">
  <label for="description" class="col-md-1 form-label">Description</label>
  <div class="col-md-4">
    <input id="description" type="text" class="form-control" name="description" value="{{ $item->description or old('description') }}" autofocus>
    @if ($errors->has('description'))
      <span class="help-block">{{ $errors->first('description') }}</span>
    @endif
  </div>
</div>

<div class="row form-group{{ $errors->has('retail_price') ? ' has-error' : '' }}">
  <label for="retail_price" class="col-md-1 form-label">Retail Price</label>
  <div class="col-md-4">
    <input id="retail_price" type="number" min="1" max="999.99" step="0.01" class="form-control" name="retail_price" value="{{ $item->retail_price or old('retail_price') }}" required autofocus>
    @if ($errors->has('retail_price'))
      <span class="help-block">{{ $errors->first('retail_price') }}</span>
    @endif
  </div>
</div>

<div class="row form-group{{ $errors->has('wholesale_price') ? ' has-error' : '' }}">
  <label for="wholesale_price" class="col-md-1 form-label">Wholesale Price</label>
  <div class="col-md-4">
    <input id="wholesale_price" type="number" min="1" max="999.99" step="0.01" class="form-control" name="wholesale_price" value="{{ $item->wholesale_price or old('wholesale_price') }}" required autofocus>
    @if ($errors->has('wholesale_price'))
      <span class="help-block">{{ $errors->first('wholesale_price') }}</span>
    @endif
  </div>
</div>

<div class="row form-group">
	<div class="col-md-1"></div>
  <div class="col-md-4">
  	@if(Route::currentRouteName() == 'items.edit')
			@include('includes.update_confirm')
		@else
			<button type="submit" class="btn btn-success pull-right">Create</button>
		@endif
    <button type="button" class="btn btn-default pull-right" data-toggle="modal" data-target="#myModal" style="margin-right: 0.5em;">Back to Items</button>
  	<div class="modal fade" id="myModal" role="dialog">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title" style="font-family: Raleway, sans-serif; font-weight: 300; font-size: 14px;">Cancel Add/Edit Item</h4>
          </div>
          <div class="modal-body">
          	<p>Are you sure you want to cancel?</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
            <a href="{{ route('items.index') }}" id="positiveBtn">
              <button type="button" class="btn btn-danger">Yes</button>
             </a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<!-- 	
	<br>
	<div class = "submit">
		@if(\Request::route()->getName() == 'items.edit')
			@include('includes.update_confirm')
		@else
			{!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
		@endif
		
                
              </div>
           
 -->