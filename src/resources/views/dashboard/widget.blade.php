<div class="dashboard-widget @foreach($widget->width as $size => $col) @if($size === 'sm') col-{{ $col }} @else col-{{ $size }}-{{ $col }} @endif @endforeach()" data-widget-name="{{ $widget->slug }}" data-widget-edit="{{ $widget->isEditable() ? 'yes' : 'no' }}">
    {!! $widget->render() !!}
</div>