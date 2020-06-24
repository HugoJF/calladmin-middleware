@extends('layouts.appv2')

@section('content')
    <div class="flex flex-col w-full items-center">
        <!-- Report -->
        <div class="w-3/4">
            <!-- Header -->
            <div class="flex items-center">
                <!-- Votes -->
                <div class="flex flex-col mr-6 items-center justify-center text-4xl">
                    <i class="text-gray-700 fa fa-chevron-up" aria-hidden="true"></i>
                    <span>1</span>
                    <i class="text-gray-700 fa fa-chevron-down" aria-hidden="true"></i>
                </div>

                <!-- Info -->
                <div class="flex-grow">
                    <!-- Reason + Date -->
                    <div class="flex justify-between mb-6">
                        <div class="flex items-baseline">
                            <h2 class="mr-4 text-2xl font-bold">passando call pra outro time</h2>
                            <small class="text-gray-600">4 hours ago</small>
                        </div>

                        <div class="flex">
                            <!-- Demo -->
                            <div class="flex items-center justify-center px-3 py-1 mr-2 bg-blue-700 hover:bg-blue-600 rounded-md shadow-sm hover:shadow cursor-pointer">
                                <i class="mr-3 text-gray-200 text-lg fa fa-download" aria-hidden="true"></i>
                                <span class="text-gray-100">Demo</span>
                            </div>
                            <!-- Correct -->
                            <div class="flex items-center justify-center px-3 py-1 mr-2 bg-green-700 hover:bg-green-600 rounded-md shadow-sm hover:shadow cursor-pointer">
                                <i class="mr-3 text-gray-200 text-lg fa fa-thumbs-up" aria-hidden="true"></i>
                                <span class="text-gray-100">Correct</span>
                            </div>
                            <!-- Incorrect -->
                            <div class="flex items-center justify-center px-3 py-1 mr-2 bg-red-700 hover:bg-red-600 rounded-md shadow-sm hover:shadow cursor-pointer">
                                <i class="mr-3 text-gray-200 text-lg fa fa-thumbs-down" aria-hidden="true"></i>
                                <span class="text-gray-100">Incorrect</span>
                            </div>

                            <!-- Ignore -->
                            <div class="flex items-center justify-center px-3 py-1 mr-2 text-gray-600 hover:text-yellow-600 border border-gray-400 hover:border-yellow-400 cursor-pointer rounded-md shadow-sm">
                                <i class="text-xl fa fa-ban" aria-hidden="true"></i>
                            </div>

                            <!-- Delete -->
                            <div class="flex items-center justify-center px-3 py-1 mr-2 text-gray-600 hover:text-red-600 border border-gray-400 hover:border-red-400 cursor-pointer rounded-md shadow-sm">
                                <i class="text-xl fa fa fa-trash-o" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="flex">
                        <!-- 1st column -->
                        <div class="w-1/3">
                            <h3 class="text-xl font-medium">Reporter:</h3>

                            <!-- Player info -->
                            <div class="flex">
                                <span class="mr-2 text-gray-800">Moov</span>
                                <span class="py-1 px-2 mx-1 text-gray-100 bg-green-700 text-xs font-bold rounded tracking-wide">R: 5/6</span>
                                <span class="py-1 px-2 mx-1 text-gray-100 bg-green-700 text-xs font-bold rounded tracking-wide">V: 56/63</span>
                                <span class="py-1 px-2 mx-1 text-gray-100 bg-green-700 text-xs font-bold rounded tracking-wide">T: 0/3</span>
                            </div>

                            <!-- Status -->
                            <div class="mt-4">
                                <div class="inline-block px-3 py-1 text-gray-200 bg-gray-800 text-2xl font-bold shadow-lg">PENDING DECISION</div>
                            </div>
                        </div>

                        <!-- 2nd column -->
                        <div class="w-1/3">
                            <h3 class="text-xl font-medium">Target:</h3>

                            <!-- Player info -->
                            <div class="flex">
                                <span class="mr-2 text-gray-800">Frezan</span>
                                <span class="py-1 px-2 mx-1 text-gray-100 bg-green-700 text-xs font-bold rounded tracking-wide">R: 2/3</span>
                                <span class="py-1 px-2 mx-1 text-gray-100 bg-green-700 text-xs font-bold rounded tracking-wide">V: 22/36</span>
                                <span class="py-1 px-2 mx-1 text-gray-100 bg-green-700 text-xs font-bold rounded tracking-wide">T: 0/1</span>
                            </div>

                            <!-- Server -->
                            <div class="mt-6">
                                <span class="text-xl font-medium font-mono">177.54.150.15:27004</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="my-10">
                <video class="w-full" controls>
                    <source src="https://minio.epsilon.denerdtv.com/calladmin/video/1800.mp4" type="video/mp4">
                </video>
            </div>

            <!-- Comment box -->
            <div>
                <!-- Form -->
                <div class="px-4 flex items-center justify-center border border-gray-700 rounded">
                    <input class="py-3 mr-2 flex-grow w-full bg-transparent select-none outline-none" type="text">
                    <i class="text-xl fa fa-paper-plane" aria-hidden="true"></i>
                </div>

                <!-- Comments -->
                <div>
                    <!-- Comment -->
                    <div class="my-4 flex items-center">
                        <img class="w-16 h-16 mr-4 rounded-full" src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/d7/d735df2c52a8959e1d3f14e469c9c1c554039a6f_full.jpg">
                        <div>
                            <h4 class="text-lg font-bold">Moov</h4>
                            <div class="text-gray-400">Esse report não tem sentido nenhum.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
