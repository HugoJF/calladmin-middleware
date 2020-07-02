@extends('layouts.appv2')

@section('content')
    <div class="flex flex-col w-full items-center">
        <!-- Report -->
        <div class="w-3/4">
            <!-- Header -->
            <div class="mb-8 flex items-center">
                <!-- Votes -->
                <div class="flex flex-col mr-6 items-center justify-center text-4xl">
                    <i class="text-gray-400 fa fa-chevron-up" aria-hidden="true"></i>
                    <span>1</span>
                    <i class="text-gray-400 fa fa-chevron-down" aria-hidden="true"></i>
                </div>

                <!-- Info -->
                <div class="flex-grow">
                    <!-- Reason + Date -->
                    <div class="flex justify-between">
                        <h2 class="mr-4 text-3xl font-medium">passando call pra outro time</h2>

                        <div class="flex">
                            <!-- Demo -->
                            <div class="flex items-center justify-center px-3 py-1 mr-2 bg-blue-700 hover:bg-blue-600 shadow-sm hover:shadow cursor-pointer rounded">
                                <i class="mr-3 text-gray-200 text-lg fa fa-download" aria-hidden="true"></i>
                                <span class="text-gray-100">Demo</span>
                            </div>
                            <!-- Correct -->
                            <div class="flex items-center justify-center px-3 py-1 mr-2 bg-green-700 hover:bg-green-600 shadow-sm hover:shadow cursor-pointer rounded">
                                <i class="mr-3 text-gray-200 text-lg fa fa-thumbs-up" aria-hidden="true"></i>
                                <span class="text-gray-100">Correct</span>
                            </div>
                            <!-- Incorrect -->
                            <div class="flex items-center justify-center px-3 py-1 mr-2 bg-red-700 hover:bg-red-600 shadow-sm hover:shadow cursor-pointer rounded">
                                <i class="mr-3 text-gray-200 text-lg fa fa-thumbs-down" aria-hidden="true"></i>
                                <span class="text-gray-100">Incorrect</span>
                            </div>

                            <!-- Ignore -->
                            <div class="flex items-center justify-center px-3 py-1 mr-2 bg-gray-800 text-gray-400 hover:text-gray-300 hover:bg-gray-700 cursor-pointer shadow-sm rounded">
                                <i class="text-xl fa fa-ban" aria-hidden="true"></i>
                            </div>

                            <!-- Delete -->
                            <div class="flex items-center justify-center px-3 py-1 mr-2 bg-gray-800 text-gray-400 hover:text-gray-300 hover:bg-gray-700 cursor-pointer shadow-sm rounded">
                                <i class="text-xl fa fa fa-trash-o" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Badges -->
                    <div class="flex p-1 mb-4 text-sm text-gray-400 tracking-tight">
                        <p class="flex items-center mr-4">
                            <i class="mr-1 text-gray-400 fa fa-map-marker" aria-hidden="true"></i>
                            <span class="text-sm">177.54.150.15:27001</span>
                        </p>
                        <p class="flex items-center">
                            <i class="mr-1 text-gray-400 fa fa-clock-o" aria-hidden="true"></i>
                            <span class="text-sm">7 hours ago</span>
                        </p>
                    </div>

                    <!-- Details -->
                    <div class="flex">
                        <!-- 1st column -->
                        <div class="w-1/3 flex-grow">
                            <h3 class="text-xl font-medium">Reporter:</h3>

                            <!-- Player info -->
                            <div class="pl-2 flex">
                                <span class="mr-2 text-gray-200">Moov</span>
                                <span class="py-1 px-2 mx-1 text-gray-100 bg-green-700 text-xs font-bold tracking-wide rounded">R: 5/6</span>
                                <span class="py-1 px-2 mx-1 text-gray-100 bg-green-700 text-xs font-bold tracking-wide rounded">V: 56/63</span>
                                <span class="py-1 px-2 mx-1 text-gray-100 bg-green-700 text-xs font-bold tracking-wide rounded">T: 0/3</span>
                            </div>

                            <!-- Status -->
                            <div class="mt-6">
                                <div class="inline-block px-3 py-1 text-gray-200 bg-gray-800 text-2xl font-bold shadow-lg rounded">PENDING DECISION</div>
                            </div>
                        </div>

                        <!-- 2nd column -->
                        <div class="w-1/3 flex-grow">
                            <h3 class="text-xl font-medium">Target:</h3>

                            <!-- Player info -->
                            <div class="pl-2 flex">
                                <span class="mr-2 text-gray-200">Frezan</span>
                                <span class="py-1 px-2 mx-1 text-gray-100 bg-green-700 text-xs font-bold tracking-wide rounded">R: 2/3</span>
                                <span class="py-1 px-2 mx-1 text-gray-100 bg-green-700 text-xs font-bold tracking-wide rounded">V: 22/36</span>
                                <span class="py-1 px-2 mx-1 text-gray-100 bg-green-700 text-xs font-bold tracking-wide rounded">T: 0/1</span>
                            </div>

                            <!-- Server -->
                            <div class="mt-4 hidden">
                                <div class="inline-block px-3 py-1 text-gray-200 bg-gray-800 text-2xl font-mono font-bold shadow-lg rounded">177.54.150.15:27004</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="flex">
                <!-- Players -->
                <div class="w-1/2">
                    <h3 class="mb-2 text-2xl font-medium">Players</h3>
                    <div class="px-4">
                        <table class="w-full">
                            <tbody>
                            <tr class="text-gray-400 border-b border-gray-800">
                                <td class="flex items-center py-2 tracking-tighter select-all">
                                    <div class="w-2 h-2 mr-3 bg-gray-700 rounded-full shadow"></div>
                                    <code>STEAM_1:1:417968068</code>
                                </td>
                                <td>Ne$Ka</td>
                            </tr>
                            <tr class="text-gray-400 border-b border-gray-800">
                                <td class="flex items-center py-2 tracking-tighter select-all">
                                    <div class="w-2 h-2 mr-3 bg-gray-700 rounded-full shadow"></div>
                                    <code>STEAM_1:0:50060552</code>
                                </td>
                                <td>zanVz</td>
                            </tr>
                            <tr class="border-b border-gray-800">
                                <td class="flex items-center py-2 tracking-tighter select-all">
                                    <div class="w-2 h-2 mr-3 bg-blue-700 rounded-full shadow"></div>
                                    <code>STEAM_1:1:81600535</code>
                                </td>
                                <td>mth e-e</td>
                            </tr>
                            <tr class="text-gray-400 border-b border-gray-800">
                                <td class="flex items-center py-2 tracking-tighter select-all">
                                    <div class="w-2 h-2 mr-3 bg-gray-700 rounded-full shadow"></div>
                                    <code>STEAM_1:0:182989492</code>
                                </td>
                                <td>joaohgaml</td>
                            </tr>
                            <tr class="border-b border-gray-800">
                                <td class="flex items-center py-2 tracking-tighter select-all">
                                    <div class="w-2 h-2 mr-3 bg-red-700 rounded-full shadow"></div>
                                    <code>STEAM_1:0:442181909</code>
                                </td>
                                <td>advisory</td>
                            </tr>
                            <tr class="text-gray-400 border-b border-gray-800">
                                <td class="flex items-center py-2 tracking-tighter select-all">
                                    <div class="w-2 h-2 mr-3 bg-gray-700 rounded-full shadow"></div>
                                    <code>STEAM_1:1:216418201</code>
                                </td>
                                <td>dgt serious?</td>
                            </tr>
                            <tr class="text-gray-400 border-b border-gray-800">
                                <td class="flex items-center py-2 tracking-tighter select-all">
                                    <div class="w-2 h-2 mr-3 bg-gray-700 rounded-full shadow"></div>
                                    <code>STEAM_1:0:76017446</code>
                                </td>
                                <td>Gdm</td>
                            </tr>
                            <tr class="text-gray-400">
                                <td class="flex items-center py-2 tracking-tighter select-all">
                                    <div class="w-2 h-2 mr-3 bg-gray-700 rounded-full shadow"></div>
                                    <code>STEAM_1:1:122362167</code>
                                </td>
                                <td>꧁ঔৣ☬✞daArkkkkk✞☬</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Chat messages -->
                <div class="w-1/2">
                    <h3 class="mb-2 text-2xl font-medium">Chat</h3>
                    <ul class="pl-4 list-unstyled">
                        <li class="my-2 text-gray-400">
                            <span class="text-gray-300 font-medium">advisory: </span> awp EL
                        </li>
                        <li class="my-2 text-gray-400">
                            <span class="text-gray-300 font-medium">advisory: </span> vai tr alg humildade
                        </li>
                        <li class="my-2 text-gray-400">
                            <span class="text-gray-300 font-medium"> ꧁ঔৣ☬✞daArkkkkk✞☬: </span> [
                        </li>
                        <li class="my-2 text-gray-400">
                            <span class="text-gray-300 font-medium">꧁ঔৣ☬✞daArkkkkk✞☬:</span> nao
                        </li>
                        <li class="my-2 text-gray-400">
                            <span class="text-gray-300 font-medium"> mth e-e:</span> mid
                        </li>
                        <li class="my-2 text-gray-400">
                            <span class="text-gray-300 font-medium"> advisory:</span> mid pra tr
                        </li>
                        <li class="my-2 text-gray-400">
                            <span class="text-gray-300 font-medium"> advisory:</span> carroça
                        </li>
                        <li class="my-2 text-gray-400">
                            <span class="text-gray-300 font-medium">꧁ঔৣ☬✞daArkkkkk✞☬:</span> drop
                        </li>
                        <li class="my-2 text-gray-400">
                            <span class="text-gray-300 font-medium">Ne$Ka:</span> !calladmin
                        </li>
                        <li class="my-2 text-gray-400">
                            <span class="text-gray-300 font-medium">꧁ঔৣ☬✞daArkkkkk✞☬:</span> drop
                        </li>
                        <li class="my-2 text-gray-400">
                            <span class="text-gray-300 font-medium">꧁ঔৣ☬✞daArkkkkk✞☬:</span> drop fdp
                        </li>
                        <li class="my-2 text-gray-400">
                            <span class="text-gray-300 font-medium">꧁ঔৣ☬✞daArkkkkk✞☬:</span> o amarelo da trolando
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Body -->
            <div class="my-10 overflow-hidden rounded">
                <video class="w-full" controls>
                    <source src="https://minio.epsilon.denerdtv.com/calladmin/video/1800.mp4" type="video/mp4">
                </video>
            </div>

            <!-- Comment box -->
            <div>
                <!-- Form -->
                <div class="px-4 mb-4 flex items-center justify-center border border-gray-700 rounded shadow">
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
                            <div class="ml-2 text-gray-300">Esse report não tem sentido nenhum.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
