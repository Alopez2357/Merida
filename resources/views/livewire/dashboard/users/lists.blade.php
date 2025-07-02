@section('title','Usuarios del Sistema')
<section class="w-full h-full overflow-y-auto overflow-x-auto py-4">
    <div class="container">
        <div class="flex flex-wrap space-y-6">
            <div class="flex justify-end md:justify-center p-4 w-full  ">
                <a href="{{ route('dashboard.users.create') }}" class="rounded-lg shadow-lg py-2 px-4 bg-green-600 hover:bg-green-500 text-white">
                    <i class="fas fa-plus"></i> Nuevo Usuario</a>
            </div>
            <div class="p-4 w-auto md:w-full bg-white rounded-lg shadow">
                <table class="w-full table table-auto">
                    <thead class="bg-black text-white">
                    <tr>
                        <th  data-label="Id" class="text-center p-2">id</th>
                        <th  data-label="Name" class="text-center p-2">name</th>
                        <th  data-label="Correo" class="text-center p-2">correo</th>
                        <th  data-label="Rol" class="text-center p-2">Rol</th>
                        <th  data-label="Created" class="text-center p-2">created_at</th>
                        <th  data-label="Editar" class="text-center p-2">Editar</th>
                        @role('SuperAdmin')
                        <th  data-label="Borrar" class="text-center p-2">Borrar</th>
                        @endrole
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $k=>$user)
                    <tr class="{{ ($k%2)?'bg-gray-100':'bg-white' }}">
                        <td data-label="Id" class="text-center p-2">{{ $user->id }}</td>
                        <td data-label="Name" class="text-center p-2">{{ $user->name }}</td>
                        <td data-label="Email" class="text-center p-2">{{ $user->email }}</td>
                        <td data-label="Nombre" class="text-center p-2">{{ $user->getRoleNames()->first() }}</td>
                        <td data-label="Created" class="text-center p-2">{{ $user->created_at }}</td>
                        @role('SuperAdmin|Admin')
                        <td data-label="Editar" class="text-center p-2">
                                <a  class="bg-blue-600 hover:bg-blue-500 text-white rounded-lg shadow hover:shadow-lg py-2 px-4"
                                    href="{{ route('dashboard.users.edit',['id'=>$user->id]) }}">
                                    Editar
                                </a>
                            </td>
                        @endrole
                        @role('SuperAdmin')
                        <td data-label="Borrar" class="text-center p-2">
                                <form method="post" action="{{ route('dashboard.users.delete',['id'=>$user->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                    <button type="submit"  class="bg-red-600 hover:bg-red-500 text-white rounded-lg shadow hover:shadow-lg py-2 px-4">
                                        Borrar
                                    </button>
                                </form>
                        </td>
                        @endrole
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div>
                {{ $users->links() }}
            </div>

        </div>
    </div>
</section>

