<div class="px-4">
    <div class="mx-auto w-full rounded-md bg-white shadow-lg  p-2 border">
        @foreach($dates as $d=>$date)
            <div class="w-full bg-white ">
                <h3 class="w-full font-semibold p-2 bg-gray-50 rounded-md text-center">{{ $d }}</h3>
                <div class="w-full flex flex-wrap text-xs justify-end">
                    @foreach($date as $M=>$month)
                        <div class="w-1/3 xl:w-1/4 p-1">
                            <button onclick="selectMonth({{ $d }},{{ $M }})" wire:click="selectMonth({{ $d }},{{ $M }})"
                                    class="{{ ($Year==$d && $Month==$M)?'bg-green-600 text-white hover:bg-green-500':'bg-white text-black hover:bg-gray-50' }}
                                            block w-full border p-2 rounded-md hover:shadow  text-center cursor-pointer">
                                {{$month}}
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
