@extends('layout')
@section('content')
<h2>アカウント登録</h2>

<form action="{{ $base_url . '/account/register' }}" method="post">
  <input type="hidden" name="_token" value="{{ $_token }}">

  <!-- エラーメッセージ ここから -->
  @if (isset($errors) && count($errors) > 0)
  <ul class="error_list">
    @foreach ($errors as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
  @endif
  <!-- ここまで -->

  <table>
    <tbody>
      <tr>
        <th>ユーザID</th>
        <td>
          <input type="text" name="user_name" value="{{ $user_name }}">
        </td>
      </tr>
      <tr>
        <th>パスワード</th>
        <td>
          <input type="password" name="password" value="{{ $password }}">
        </td>
      </tr>
    </tbody>
  </table>
  <p><input type="submit" value="登録"></p>
</form>
@endsection