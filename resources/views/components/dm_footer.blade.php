<footer class="relative mt-20 bg-gray-900 text-gray-300 overflow-hidden">
    <!-- Decorative top gradient -->
    <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-green-500/60 to-transparent"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-14">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10">
            <!-- Brand -->
            <div class="lg:col-span-1">
                <a href="{{ url('/') }}" class="flex items-center gap-2.5">
                    <span
                        class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-green-500 to-green-700 text-white shadow-lg shadow-green-600/30">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22c4.97 0 9-4.03 9-9-4.97 0-9 4.03-9 9Z" />
                            <path d="M12 22c0-4.97-4.03-9-9-9 0 4.97 4.03 9 9 9Z" />
                            <path d="M12 13c0-3.31 2.69-6 6-6 0 3.31-2.69 6-6 6Z" />
                            <path d="M12 13V2" />
                        </svg>
                    </span>
                    <span class="font-display text-xl font-extrabold tracking-tight text-white">
                        Digital<span class="text-green-400">Mandi</span>
                    </span>
                </a>
                <p class="mt-4 text-sm text-gray-400 leading-relaxed max-w-xs">
                    The smart agricultural marketplace connecting farmers, buyers and commission shops with real-time
                    market rates.
                </p>
            </div>

            <!-- Explore -->
            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Explore</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ url('/') }}" class="hover:text-green-400 transition-base">Home</a></li>
                    <li><a href="{{ url('rates') }}" class="hover:text-green-400 transition-base">Market Rates</a></li>
                    <li><a href="{{ url('deals') }}" class="hover:text-green-400 transition-base">Deals</a></li>
                    <li><a href="{{ url('commission-shops') }}" class="hover:text-green-400 transition-base">Commission
                            Shops</a></li>
                </ul>
            </div>

            <!-- Company -->
            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Company</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ url('terms') }}" class="hover:text-green-400 transition-base">Terms &
                            Conditions</a></li>
                    <li><a href="{{ url('privacy') }}" class="hover:text-green-400 transition-base">Privacy Policy</a>
                    </li>
                    <li><a href="https://play.google.com/store/apps/details?id=com.kisan.digitalmandi&hl=en" target="_blank"
                            rel="noopener" class="hover:text-green-400 transition-base">Download App</a></li>
                </ul>
            </div>

            <!-- Connect -->
            <div>
                <h3 class="text-sm font-semibold text-white uppercase tracking-wider mb-4">Connect</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="https://www.facebook.com/kisanstock" target="_blank" rel="noopener" aria-label="Facebook"
                        class="flex items-center justify-center w-10 h-10 rounded-xl bg-white/5 hover:bg-green-600 text-gray-300 hover:text-white transition-base">
                        <span class="bi bi-facebook text-lg"></span>
                    </a>
                    <a href="https://youtube.com/@kisanstock/" target="_blank" rel="noopener" aria-label="YouTube"
                        class="flex items-center justify-center w-10 h-10 rounded-xl bg-white/5 hover:bg-green-600 text-gray-300 hover:text-white transition-base">
                        <span class="bi bi-youtube text-lg"></span>
                    </a>
                    <a href="https://tiktok.com/@kisanstock" target="_blank" rel="noopener" aria-label="TikTok"
                        class="flex items-center justify-center w-10 h-10 rounded-xl bg-white/5 hover:bg-green-600 text-gray-300 hover:text-white transition-base">
                        <span class="bi bi-tiktok text-lg"></span>
                    </a>
                </div>
                <a href="https://play.google.com/store/apps/details?id=com.kisan.digitalmandi&hl=en" target="_blank"
                    rel="noopener"
                    class="mt-5 inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-green-600 to-green-700 rounded-xl hover:shadow-lg hover:shadow-green-600/30 transition-base">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                        <path
                            d="M3 20.5v-17c0-.6.3-1.1.8-1.4l10.6 9.9L3.8 21.9c-.5-.3-.8-.8-.8-1.4Zm13.8-7.5 2.7 2.5-2.7 2.5-3-2.7 3-2.3Zm-2.4-2.2L5.4 2.4 16 8.5l-1.6 1.8Zm0 4.4 1.6 1.8L5.4 22.1l9-8.4Z" />
                    </svg>
                    Google Play
                </a>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Digital Mandi. All rights reserved.</p>
            <p class="text-xs text-gray-600">Empowering agriculture with technology 🌱</p>
        </div>
    </div>
</footer>
