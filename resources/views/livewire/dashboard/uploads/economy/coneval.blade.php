@section('title','Carga CONEVAL')
<section class="py-2 overflow-y-auto">
    <div class="container">

        @livewire('dashboard.uploads.file',['type_id'=>$type_id,'key'=>$key,'regexp'=>$regexp])
        @livewire('dashboard.uploads.history', ['type_id' => $type_id])
    </div>
</section>
