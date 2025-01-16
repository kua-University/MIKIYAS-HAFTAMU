<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Form</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="bg-white rounded-lg shadow-lg p-8 w-96">
        <h2 class="text-2xl font-bold mb-6 text-center">Payment Form</h2>
         @if(session('success'))
         <div class=" bg-green-200 text-green-800 py-2 px-4 mb-4 rounded">{{session('success')}}</div>
         @elseif(session('error'))
         <div class=" bg-green-200 text-green-800 py-2 px-4 mb-4 rounded">{{session('error')}}</div>
         @endif



    @if($errors->any())
        <div class="bg-red-200 text-red-800 py-2 px-4 mb-4 rounded">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
         <form method="POST" action="{{ route('pay') }}">
            @csrf
            <div class="mb-4">
                <label for="first_name" class="block text-gray-700">First Name</label>
                <input type="text" id="first_name" name="first_name" required class="mt-1 block w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-500"/>
            </div>

            <div class="mb-4">
                <label for="last_name" class="block text-gray-700">Last Name</label>
                <input type="text" id="last_name" name="last_name" required class="mt-1 block w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-500"/>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" id="email" name="email" required class="mt-1 block w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-500"/>
            </div>

            <div class="mb-4">
                <label for="amount" class="block text-gray-700">Amount (Birr)</label>
                <input type="number" id="amount" name="amount" required class="mt-1 block w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-500"/>
            </div>

            <div>
                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600 focus:outline-none focus:bg-blue-600">
                    Pay
                </button>
            </div>
        </form>
    </div>

</body>
</html>