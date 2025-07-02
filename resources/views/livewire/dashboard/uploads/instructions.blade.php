@section('title','Instrucciones Carga de Archivos')
<section>
<div class="md:container px-2 md:px-0">
    <div class="my-4 p-6 rounded-lg shadow-lg bg-white flex flex-wrap">

        <div class="w-full py-4">
            <h2 class="font-bold text-lg">1. Extensiones de archivo </h2>
            <p class="p-4">Estos son extensiones permitidas</p>
            <ul class="list-disc font-semibold pl-8">
                <li>.xls</li>
                <li>.xlsx</li>
                <li>.csv</li>
            </ul>
            <div class="p-4 flex w-full ">
                <div class="w-full md:w-1/2">
                    <img class="mx-auto w-full md:w-2/3" src="{{ asset('img/instructions/0.png') }}" alt="">
                </div>
            </div>
        </div>

        <div class="w-full py-4">
            <h2 class="font-bold text-lg">2. Sin encabezados</h2>
            <p class="p-4">Deben eliminarse todos los encabezados del archivo, debe iniciarse solo las filas con información a cargar </p>
            <div class="p-4 flex w-full ">
                <div class="w-full md:w-1/2">
                    <img class="mx-auto w-full md:w-2/3" src="{{ asset('img/instructions/1.png') }}" alt="">
                </div>
                <div class="w-full md:w-1/2">
                    <img class="mx-auto w-full md:w-2/3" src="{{ asset('img/instructions/2.png') }}" alt="">
                </div>
            </div>
        </div>

        <div class="w-full py-4">
            <h2 class="font-bold text-lg">3. Sin Filas o colmnas vacias</h2>
            <p class="p-4">Deben eliminarse las columnas y filas completamente vacias, el proceso es secuencial y fallará si encuentra filas o columnas sin datos utiles.</p>
            <div class="p-4 flex w-full ">
                <div class="w-full md:w-1/2">
                    <img class="mx-auto w-full md:w-2/3" src="{{ asset('img/instructions/4.png') }}" alt="">
                </div>
                <div class="w-full md:w-1/2">
                    <img class="mx-auto w-full md:w-2/3" src="{{ asset('img/instructions/5.png') }}" alt="">
                </div>
            </div>
        </div>

        <div class="w-full py-4">
            <h2 class="font-bold text-lg">4. Sin celdas combinadas</h2>
            <p class="p-4">Solo debe dejarse el dato único en la primera celda del grupo original</p>
            <div class="p-4 flex w-full ">
                <div class="w-full md:w-1/2">
                    <img class="mx-auto w-full md:w-2/3" src="{{ asset('img/instructions/6.png') }}" alt="">
                </div>
                <div class="w-full md:w-1/2">
                    <img class="mx-auto w-full md:w-2/3" src="{{ asset('img/instructions/7.png') }}" alt="">
                </div>
            </div>
        </div>

        <div class="w-full py-4">
            <h2 class="font-bold text-lg">5. Formatos de fecha de origen</h2>
            <p class="p-4">Los formatos de fecha deben ser los que originalmente contiene el archivo.</p>
            <h3 class="font-semibold">Formatos Permitidos:</h3>
            <ul class="list-disc  pl-8">
                <li>Feb 2010</li>
                <li>5/1/2022</li>
            </ul>
            <h3 class="font-semibold">Formatos NO Permitidos:</h3>
            <ul class="list-disc pl-8">
                <li>Feb-2010</li>
                <li>01 Feb 2010</li>
                <li>primero de febrero 2010</li>
            </ul>
            <div class="p-4 flex w-full ">
                <div class="w-full md:w-1/2">
                    <img class="mx-auto w-full md:w-2/3" src="{{ asset('img/instructions/8.png') }}" alt="">
                </div>
                <div class="w-full md:w-1/2">
                    <img class="mx-auto w-full md:w-2/3" src="{{ asset('img/instructions/9.png') }}" alt="">
                </div>
            </div>
            <h3 class="font-semibold">Intervalos Permitidos:</h3>
            <ul class="list-disc  pl-8">
                <li>Ene 2010 - Feb 2010</li>
                <li>5/1/2022 - 5/1/2023</li>
            </ul>
            <h3 class="font-semibold">Intervalos NO Permitidos:</h3>
            <ul class="list-disc pl-8">
                <li>Ene-2010-Feb-2010</li>
                <li>2010 Ene - 2010 Feb</li>
            </ul>
        </div>

    </div>
</div>
</section>

