@props(['label', 'href' => null, 'disabled' => false])

@if(!$disabled)
    <div
        {{ $attributes->merge([
            'class' => 'subheadline'
        ]) }}
    >
        @if($href)
            <a
                href="{{ $href }}"
                @class([
                    'd-flex',
                    'fw-bold',
                    'text-uppercase',
                    'align-items-center',
                ])
            >
                <div class="flex-grow-1">
                    {!! $label !!}
                </div>
                <div>
                    <i class="fa-solid fa-caret-up fa-rotate-90"></i>
                </div>
            </a>
        @else
            <div class="d-flex">
                <div class="flex-grow-1 fw-bold text-uppercase">
                    {!! $label !!}
                </div>
                {{ $options ?? '' }}
            </div>
        @endif
    </div>
    @if($slot->isNotEmpty())
        {{--@todo removed .flex-grow-1 e.g. games-latest.blade --}}
        <div class="subheadline-body flex-grow-1">
            {{ $slot }}
        </div>
    @endif
@endif
