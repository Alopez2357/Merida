<div>
    <div class="w-full bg-white rounded-xl  border">
        @foreach($dates as $y=>$years)
            <div class="w-full flex-wrap">
                <div class="w-full bg-gray-200 rounded-xl font-bold text-center">{{ $years['name'] }}</div>
                <div class="w-full flex">
                    @foreach($years['quarters'] as $q=>$quarter)
                        <div class="w-1/3 p-2 flex justify-center">
                            <button onclick="selectQuarter({{$years['name']}},{{$quarter}})" wire:click="selectQuarter({{$years['name']}},{{$quarter}})"
                                    class="w-full rounded-lg p-2 border {{ ($years['name']==$Year && $quarter==$Quarter)?'bg-green-500 text-white':'bg-white' }}
                                            hover:bg-green-500 hover:text-white">{{  translateRomanNumerals($quarter) }}</button>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
