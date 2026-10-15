    <!-- WIEMER-STYLE GLOBAL LOADING SCREEN -->
    <div id="global-loader">
        <!-- Center: Motion GIF Animation (No Background, Black Logo) -->
        <div id="loader-center-content" class="flex-1 flex flex-col items-center justify-center transition-all duration-400 px-4">
            <img src="{{ asset('motionnobg.gif') }}" alt="Notisse Motion" class="w-36 sm:w-48 md:w-56 h-auto opacity-95 pointer-events-none">
        </div>

        <!-- Bottom: Black Loading Bar (Flush at bottom edge) -->
        <div class="w-full h-[3.5px] sm:h-[4px] bg-black/15 relative overflow-hidden">
            <div id="loader-progress-bar"
                 class="h-full bg-black"
                 style="transform: scaleX(0);"></div>
        </div>
    </div>

    <script>
        // Immediate Execution of Wiemer-Style Preloader Logic
        (function initWiemerLoader() {
            const loader = document.getElementById('global-loader');
            if (!loader) return;

            const progressBar = document.getElementById('loader-progress-bar');
            const centerContent = document.getElementById('loader-center-content');

            // Temporarily lock scroll while preloader is active
            document.documentElement.style.overflow = 'hidden';
            document.body.style.overflow = 'hidden';

            let isFinishing = false;
            const duration = 4530; // Matches exact motionnobg.gif animation duration (151 frames * 30ms = 4530ms)
            let startTimestamp = null;
            let animationFrameId = null;

            // Smooth natural ease matching the handwriting drawing tempo
            function progressEase(t) {
                return t < 0.5 
                    ? 2 * t * t 
                    : 1 - Math.pow(-2 * t + 2, 2) / 2;
            }

            function updateUI(percent) {
                if (progressBar) {
                    progressBar.style.transform = `scaleX(${percent / 100})`;
                }
            }

            updateUI(0);

            function frameStep(timestamp) {
                if (!startTimestamp) startTimestamp = timestamp;
                const elapsed = timestamp - startTimestamp;
                const rawProgress = Math.min(1, elapsed / duration);
                const eased = progressEase(rawProgress);
                const targetPercent = Math.min(100, eased * 100);

                updateUI(targetPercent);

                if (rawProgress < 1) {
                    animationFrameId = requestAnimationFrame(frameStep);
                } else {
                    updateUI(100);
                    finishLoader();
                }
            }

            animationFrameId = requestAnimationFrame(frameStep);

            let finishTimeout1 = null;
            let finishTimeout2 = null;
            let finishTimeout3 = null;

            function finishLoader() {
                if (isFinishing) return;
                isFinishing = true;

                updateUI(100);

                // Hold briefly when logo animation finishes before curtain pull-up
                finishTimeout1 = setTimeout(() => {
                    if (centerContent) centerContent.classList.add('loader-fade-out');

                    // Signature Wiemer Curtain Pull-Up (translates up off-screen)
                    finishTimeout2 = setTimeout(() => {
                        loader.classList.add('pulled-up');

                        // Unlock page scroll
                        document.documentElement.style.overflow = '';
                        document.body.style.overflow = '';

                        // Remove from layout after slide-up finishes
                        finishTimeout3 = setTimeout(() => {
                            loader.style.display = 'none';
                        }, 1050);
                    }, 220);
                }, 180);
            }

            // Expose a replay helper for testing/previewing
            window.replayWiemerLoader = function() {
                if (animationFrameId) cancelAnimationFrame(animationFrameId);
                clearTimeout(finishTimeout1);
                clearTimeout(finishTimeout2);
                clearTimeout(finishTimeout3);
                loader.style.display = 'flex';
                loader.classList.remove('pulled-up');
                updateUI(0);
                if (centerContent) {
                    centerContent.classList.remove('loader-fade-out');
                    const img = centerContent.querySelector('img');
                    if (img) {
                        const baseSrc = img.src.split('?')[0];
                        img.src = baseSrc + '?t=' + Date.now();
                    }
                }
                document.documentElement.style.overflow = 'hidden';
                document.body.style.overflow = 'hidden';
                isFinishing = false;
                startTimestamp = null;
                animationFrameId = requestAnimationFrame(frameStep);
            };
        })();
    </script>
