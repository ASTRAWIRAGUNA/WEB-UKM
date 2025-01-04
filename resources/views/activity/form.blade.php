<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form UKM</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .form-container {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      width: 300px;
    }

    .form-container h1 {
      font-size: 1.5rem;
      margin-bottom: 20px;
      text-align: center;
      color: #333;
    }

    form label {
      font-weight: bold;
      display: block;
      margin-bottom: 5px;
      color: #555;
    }

    form input,
    form select,
    form button {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 4px;
      font-size: 1rem;
    }

    form input:focus,
    form select:focus,
    form button:focus {
      border-color: #007bff;
      outline: none;
    }

    form button {
      background-color: #007bff;
      color: #fff;
      border: none;
      cursor: pointer;
      font-weight: bold;
    }

    form button:hover {
      background-color: #0056b3;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h1>Form Pendaftaran UKM</h1>
    <form action="{{route('activities.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
      <label for="ukm-select">Pilih Nama UKM</label>
      <select id="ukm-select" name="ukm_id" required>
        @foreach ($ukms as $ukm)
          <option value="{{ $ukm->ukm_id }}">{{ $ukm->name_ukm }}</option>
        @endforeach
      </select>

      <label for="ukm-name">Nama Kegiatan</label>
      <input type="text" id="ukm-name" name="ukm-name" placeholder="Masukkan Nama Kegiatan" required>

      <label for="ukm-date">Tanggal Pendaftaran</label>
      <input type="date" id="ukm-date" name="ukm-date" required>

      <label for="ukm-photo">URL Foto</label>
      <input type="text" id="ukm-photo" name="ukm-photo" placeholder="Masukkan URL Foto" required>

      <button type="submit">Submit</button>
    </form>
  </div>
</body>
</html>
