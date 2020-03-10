@extends('layouts.dashboard')

@section('title', 'liste utilisateurs')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Liste {{ session()->get('user_role') == 'SuperAdmin' ? 'Utilisateurs' : 'Clients' }}</h1>
</div>


<div class="row" style="margin-bottom:20px;">
    <div class="col-lg-12 margin-tb">
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('users.create') }}"> Ajouter un {{ session()->get('user_role') == 'SuperAdmin' ? 'Utilisateur' : 'Client' }}</a>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif

<table class="table table-bordered">
    <tr>
      <th>Id</th>
      <th>Nom</th>
      <th>Email</th>
      <th>Roles</th>
      <th width="280px">Action</th>
    </tr>
    @foreach ($users as $key => $user)
     <tr>
       <td>{{ $user->id }}</td>
       <td>{{ $user->name }}</td>
       <td>{{ $user->email }}</td>
       <td>
         @if(!empty($user->getRoleNames()))
           @foreach($user->getRoleNames() as $v)
              <label class="badge badge-success">{{ $v }}</label>
           @endforeach
         @endif
       </td>
       <td>
          <a class="btn btn-info" href="{{ route('users.show',$user->id) }}">Détail</a>
          @if ($user->getRoleNames()[0] !== 'SuperAdmin')
          <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">Modifier</a>
          @if ($user->balance <= 0)

            <form  action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')

                <button type="submit" class="btn btn-danger">Supprimer</button>
            </form>
            @endif
            @endif
       </td>
     </tr>
    @endforeach
   </table>

   {!! $users->render() !!}

@endsection
