define(['jquery'], function($) {
    // jquery is used by the visualizer
    return {
        init: function(serverUrl, identifierKey, reports) {
            if (serverUrl && identifierKey && identifierKey.length > 0) {
                if (!window.panoramaFetched) {
                    window.panoramaFetched = true;
                    window.SERVER_URL = serverUrl;
                    window.identifierKey = identifierKey;
                    window.shouldInjectReports = reports;
                    const visualizer = document.createElement("script");
                    visualizer.src = `${serverUrl}/resources/build/moodle-visualizer.js`;
                    document.head.appendChild(visualizer);
                    function attemptInit() {
                        if (window.panoramaInit) {
                            window.panoramaInit();
                            window.panoramaInit = undefined;
                        } else {
                            setTimeout(() => {
                                attemptInit();
                            }, 50);
                        }
                    }
                    $(document).ready(attemptInit());
                }
            }
        }
    }
});