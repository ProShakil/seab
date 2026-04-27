<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt of Shakil</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex justify-center items-center min-h-screen bg-gray-100">

    <div class="relative w-[1774px] h-[887px] bg-white shadow-lg border border-red-300 rounded-xl bg-red" style="background-image: url('./Recipt-1.jpg');">
        <span class="absolute top-[490px] left-[357px] text-[48px] font-semibold tracking-[15px] text-green-700 uppercase">
            2025
        </span>
        <span class="absolute top-[268px] right-[195px] text-[35px] font-bold tracking-[10px] text-green-700 uppercase">
            2025
        </span>

        <!-- left side input field -->
        <div class="flex">
            <div class="relative mt-[275px] ml-[895px] h-[350px] w-[320px] border border-0 border-black">
                <span id="nameText" class="whitespace-nowrap font-[Poppins] text-[28px] font-semibold tracking-wide text-black uppercase overflow-hidden text-clip block"></span>
                <br>
                <span class="whitespace-nowrap font-[Poppins] text-[28px] font-semibold tracking-wide text-black uppercase overflow-hidden text-clip block">500.00/-</span>
                <br>
                <span id="amountWord"
                    class="font-[Poppins] text-[28px] font-semibold tracking-wide text-black block leading-snug line-clamp-2 overflow-hidden">
                    Five Hundred Taka Only
                </span>
    
                <span class="absolute bottom-[55px] whitespace-nowrap font-[Poppins] text-[28px] font-semibold tracking-wide text-black uppercase overflow-hidden text-clip block">25 Jun, 2026</span>
    
                <span class="absolute bottom-[-5px] whitespace-nowrap font-[Poppins] text-[28px] font-semibold tracking-wide text-black uppercase overflow-hidden text-clip block">2026044665768</span>
    
            </div>
    
            <div class="relative mt-[330px] ml-[175px] h-[368px] w-[310px] border border-0 border-black">
                <span id="nameText2" class="absolute top-[35px] whitespace-nowrap font-[Poppins] text-[20px] font-semibold tracking-wide text-black uppercase overflow-hidden text-clip block"></span>

                <span class="absolute top-[110px] whitespace-nowrap font-[Poppins] text-[20px] font-semibold tracking-wide text-black uppercase overflow-hidden text-clip block">500.00/-</span>

                <span class="absolute top-[180px] whitespace-nowrap font-[Poppins] text-[20px] font-semibold tracking-wide text-black uppercase overflow-hidden text-clip block">Five Hundred Taka Only</span>

                <span class="absolute top-[258px] whitespace-nowrap font-[Poppins] text-[20px] font-semibold tracking-wide text-black uppercase overflow-hidden text-clip block">25 Jun, 2026</span>

                <span class="absolute top-[328px] whitespace-nowrap font-[Poppins] text-[20px] font-semibold tracking-wide text-black uppercase overflow-hidden text-clip block">2026044665768</span>
            </div>

        </div>
    </div>
    <script>
        formatNameIfOverflow(
            "Shakil Ahmed",
            document.getElementById("nameText")
        );
        formatNameIfOverflow(
            "Shakil Ahmed",
            document.getElementById("nameText2")
        );
        function formatNameIfOverflow(fullName, element) {
            element.textContent = fullName;

            requestAnimationFrame(() => {
                if (element.scrollWidth > element.clientWidth) {
                    const words = fullName.trim().split(/\s+/);

                    if (words.length > 1) {
                        const lastName = words.pop();

                        const initials = words
                            .map(w => w.replace(/\./g, '')[0])
                            .filter(Boolean)
                            .join('')
                            .toUpperCase();

                        element.textContent = `${initials} ${lastName}`;
                    }
                }
            });
        }
        const amountWord = document.getElementById("amountWord");

        fitTextTo2Lines(amountWord);
        function fitTextTo2Lines(element, maxFontSize = 28, minFontSize = 14) {
            let fontSize = maxFontSize;

            element.style.fontSize = fontSize + "px";

            requestAnimationFrame(() => {
                while (element.scrollHeight > element.clientHeight && fontSize > minFontSize) {
                    fontSize -= 1;
                    element.style.fontSize = fontSize + "px";
                }
            });
        }
    </script>
</body>
</html>