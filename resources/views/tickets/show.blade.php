@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('tickets.index') }}"
                class="flex items-center text-gray-500 hover:text-gray-700 font-medium transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Daftar Tiket
            </a>

            @if (!auth()->user()->customer)
                <div class="flex gap-2">
                    <a href="{{ route('tickets.edit', $ticket) }}"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-lg text-sm font-bold hover:bg-yellow-600 shadow-sm">
                        ⚙️ Kelola Tiket
                    </a>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[calc(100vh-200px)] min-h-[600px]">

            <div class="lg:col-span-1 space-y-4 overflow-y-auto pr-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                    <div class="flex justify-between items-start mb-4">
                        <span
                            class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded text-xs font-mono font-bold">#{{ $ticket->ticket_code }}</span>
                        <span class="px-2 py-1 rounded-full text-xs font-bold border {{ $ticket->getStatusBadgeClass() }}">
                            {{ $ticket->getStatusLabel() }}
                        </span>
                    </div>

                    <h1 class="text-lg font-bold text-gray-900 mb-2">{{ $ticket->subject }}</h1>
                    <p class="text-sm text-gray-600 leading-relaxed mb-4">{{ $ticket->description }}</p>

                    @if ($ticket->photo)
                        <div class="mb-4">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-1">Lampiran Awal</p>
                            <a href="{{ Storage::url($ticket->photo) }}" target="_blank">
                                <img src="{{ Storage::url($ticket->photo) }}"
                                    class="w-full rounded-lg border hover:opacity-90 transition cursor-zoom-in">
                            </a>
                        </div>
                    @endif

                    <hr class="border-gray-100 my-4">

                    <div class="space-y-3 text-sm">
                        <div>
                            <label class="text-xs text-gray-400 block">Pelapor</label>
                            <span class="font-medium text-gray-900">{{ $ticket->customer->user->name }}</span>
                        </div>
                        <div>
                            <label class="text-xs text-gray-400 block">Waktu Laporan</label>
                            <span class="text-gray-700">{{ $ticket->created_at->format('d M Y, H:i') }}</span>
                        </div>
                        <div>
                            <label class="text-xs text-gray-400 block">Teknisi</label>
                            @if ($ticket->user)
                                <span class="flex items-center mt-1">
                                    <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                    {{ $ticket->user->name }}
                                </span>
                            @else
                                <span class="text-gray-400 italic">Belum di-assign</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 flex flex-col overflow-hidden">

                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                    <h3 class="font-bold text-gray-700 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        Diskusi / Update Progress
                    </h3>
                    <span class="text-xs text-gray-400">{{ $ticket->replies->count() }} Pesan</span>
                </div>

                <div class="flex-1 p-6 overflow-y-auto space-y-6 bg-white" id="chatContainer">
                    <div class="flex justify-center">
                        <span class="bg-gray-100 text-gray-500 text-xs px-3 py-1 rounded-full">
                            Tiket dibuat pada {{ $ticket->created_at->format('d M Y, H:i') }}
                        </span>
                    </div>

                    @foreach ($ticket->replies as $reply)
                        @php
                            $isMe = $reply->user_id == auth()->id();
                            $roleColor =
                                $reply->user->hasRole('admin') || $reply->user->hasRole('teknisi')
                                    ? 'bg-indigo-100 text-indigo-800'
                                    : 'bg-gray-100 text-gray-800';
                        @endphp

                        <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[80%] {{ $isMe ? 'order-1' : 'order-2' }}">
                                <div class="flex items-center gap-2 mb-1 {{ $isMe ? 'justify-end' : 'justify-start' }}">
                                    <span class="text-xs font-bold text-gray-700">{{ $reply->user->name }}</span>
                                    <span class="text-[10px] text-gray-400">{{ $reply->created_at->format('H:i') }}</span>
                                </div>

                                <div
                                    class="px-4 py-3 rounded-2xl text-sm shadow-sm 
                                    {{ $isMe ? 'bg-indigo-600 text-white rounded-tr-none' : 'bg-gray-100 text-gray-800 rounded-tl-none' }}">
                                    <p class="whitespace-pre-wrap">{{ $reply->message }}</p>

                                    @if ($reply->attachment)
                                        <div class="mt-2">
                                            <a href="{{ Storage::url($reply->attachment) }}" target="_blank">
                                                <img src="{{ Storage::url($reply->attachment) }}"
                                                    class="max-w-full rounded-lg border border-white/20 max-h-40 object-cover">
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($ticket->status != 'closed')
                    <div class="p-4 bg-gray-50 border-t border-gray-200">
                        <form action="{{ route('tickets.reply', $ticket) }}" method="POST" enctype="multipart/form-data"
                            class="relative">
                            @csrf
                            <div class="flex gap-2 items-end">
                                <label
                                    class="cursor-pointer p-3 text-gray-400 hover:text-indigo-600 transition bg-white border border-gray-300 rounded-lg hover:border-indigo-300">
                                    <input type="file" name="attachment" class="hidden"
                                        onchange="document.getElementById('fileName').innerText = this.files[0].name">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </label>

                                <div class="flex-1">
                                    <span id="fileName"
                                        class="text-xs text-indigo-600 block mb-1 truncate max-w-[200px]"></span>
                                    <textarea name="message" rows="1" required
                                        class="block w-full rounded-lg border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 resize-none p-3"
                                        placeholder="Tulis pesan balasan..."></textarea>
                                </div>

                                <button type="submit"
                                    class="bg-indigo-600 text-white p-3 rounded-lg hover:bg-indigo-700 transition shadow-md">
                                    <svg class="w-6 h-6 transform rotate-90" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="p-4 bg-gray-100 border-t border-gray-200 text-center text-gray-500 text-sm font-medium">
                        ⛔ Tiket ini telah ditutup. Anda tidak dapat membalas lagi.
                    </div>
                @endif
            </div>

        </div>
    </div>

    <script>
        // Auto scroll ke bawah saat halaman dimuat
        const chatContainer = document.getElementById('chatContainer');
        chatContainer.scrollTop = chatContainer.scrollHeight;
    </script>
@endsection
