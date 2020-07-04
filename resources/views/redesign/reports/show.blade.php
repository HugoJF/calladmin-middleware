@extends('layouts.appv2')

@section('content')
    <div class="flex flex-col w-full items-center">
        <!-- Report -->
        <div class="w-3/4">
            <!-- Header -->
            <div class="mb-8 flex items-center">
                <!-- Votes -->
                <div class="flex flex-col mr-6 items-center justify-center text-4xl hidden">
                    <i class="text-gray-400 fa fa-chevron-up" aria-hidden="true"></i>
                    <span>1</span>
                    <i class="text-gray-400 fa fa-chevron-down" aria-hidden="true"></i>
                </div>

                <!-- Info -->
                <div class="flex-grow">
                    <!-- Reason + Date -->
                    <div class="flex justify-between">
                        <h2 class="mr-4 text-2xl font-medium">{{ $report->reason }}</h2>

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
                    <div class="flex p-1 pl-4 mb-4 text-sm text-gray-400 tracking-tight">
                        <p class="flex items-baseline mr-8">
                            <i class="mr-2 text-gray-300 fa fa-map-marker" aria-hidden="true"></i>
                            <span class="text-sm">{{ $report->server_ip }}:{{ $report->server_port }}</span>
                        </p>
                        <p class="flex items-baseline">
                            <i class="mr-2 text-gray-300 fa fa-clock-o" aria-hidden="true"></i>
                            <span class="text-sm">{{ $report->created_at->diffForHumans() }}</span>
                        </p>
                    </div>

                    <!-- Details -->
                    <div class="flex">
                        <!-- 1st column -->
                        <div class="w-1/3 flex-grow">
                            <h3 class="text-xl font-medium">Reporter:</h3>

                            <!-- Player info -->
                            <div class="pl-2 flex items-center">
                                <div class="w-2 h-2 mr-3 bg-blue-700 rounded-full shadow"></div>
                                <span class="mr-2 text-gray-200">{{ $report->reporter_name }}</span>
                                <div class="mx-2 text-sm text-gray-400 font-mono tracking-tighter">{{ $report->reporter_steam_id }}</div>
                                @include('redesign.partials.player-stats', ['user' => $report->reporter])
                            </div>

                            <!-- Status -->
                            <div class="mt-6">
                                @include('redesign.partials.report-status')
                            </div>
                        </div>

                        <!-- 2nd column -->
                        <div class="w-1/3 flex-grow">
                            <h3 class="text-xl font-medium">Target:</h3>

                            <!-- Player info -->
                            <div class="pl-2 flex items-center">
                                <div class="w-2 h-2 mr-3 bg-red-700 rounded-full shadow"></div>
                                <span class="text-gray-200">{{ $report->target_name }}</span>
                                <div class="mx-2 text-sm text-gray-400 font-mono tracking-tighter">{{ $report->target_steam_id }}</div>
                                @include('redesign.partials.player-stats', ['user' => $report->target])
                            </div>

                            <!-- Server -->
                            <div class="mt-4 hidden">
                                <div class="inline-block px-3 py-1 text-gray-200 bg-gray-800 text-2xl font-mono font-bold shadow-lg rounded">
                                    {{ $report->server_ip }}:{{ $report->server_port }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="flex">
                <!-- Players -->
                @include('redesign.partials.player-list')

                <!-- Chat messages -->
                @include('redesign.partials.chat')
            </div>

            <!-- Body -->
            <div class="my-10 overflow-hidden rounded">
                <video class="w-full" controls>
                    <source src="https://minio.epsilon.denerdtv.com/calladmin/video/1800.mp4" type="video/mp4">
                </video>
            </div>

            <!-- Comment box -->
            <div>
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

                <!-- Form -->
                <form class="px-4 mt-4 flex items-center justify-center border border-gray-700 rounded shadow">
                    <input class="py-3 mr-2 flex-grow w-full bg-transparent select-none outline-none" type="text">
                    <button class="text-xl fa fa-paper-plane" aria-hidden="true"></button>
                </form>
            </div>
        </div>
    </div>
@endsection
