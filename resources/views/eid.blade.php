<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$payment->receipt_number}} - {{$payment->user->name}}</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        window.onload = function () {
            setTimeout(() => {
                const element = document.querySelector("#receipt");

                const opt = {
                    margin: 0,
                    filename: 'Receipt_{{$payment->id}}.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: {
                        scale: 2,
                        useCORS: true
                    },
                    jsPDF: {
                        unit: 'px',
                        format: [1774, 887],   // ✅ FIXED
                        orientation: 'landscape'
                    }
                };

                html2pdf()
                    .set(opt)
                    .from(element)
                    .save()
                    .then(() => {
                         setTimeout(() => {
                            window.close();
                        }, 1000);
                    });

            }, 1500);
        };
        </script>
</head>
<body class="flex justify-center items-center min-h-screen bg-gray-100">
    @php
        function numberToWords($number)
        {
            $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
            return ucfirst($f->format($number)) . " Taka Only";
        }
    @endphp

    <div id="receipt" class="relative w-[1774px] h-[887px] bg-white shadow-lg border border-red-300 rounded-xl bg-red" style="background-image: url('/assets/eid.jpg');">
        <span class="absolute top-[490px] left-[357px] text-[48px] font-semibold tracking-[15px] text-green-700 uppercase">
            {{ \Carbon\Carbon::parse($payment->payment_date)->format('Y') }}
        </span>
        <span class="absolute top-[268px] right-[195px] text-[35px] font-bold tracking-[10px] text-green-700 uppercase">
            {{ \Carbon\Carbon::parse($payment->payment_date)->format('Y') }}
        </span>

        <!-- left side input field -->
        <div class="flex">
            <div class="relative mt-[275px] ml-[895px] h-[350px] w-[320px] border border-0 border-black">
                <span id="nameText" class="whitespace-nowrap font-[Poppins] text-[28px] font-semibold tracking-wide text-black uppercase overflow-hidden text-clip block"></span>
                <br>
                <span class="whitespace-nowrap font-[Poppins] text-[28px] font-semibold tracking-wide text-black uppercase overflow-hidden text-clip block">{{$payment->reunionPeriod->fee}}/-</span>
                <br>
                <span id="amountWord"
                    class="font-[Poppins] text-[28px] font-semibold tracking-wide text-black block leading-snug line-clamp-2 overflow-hidden">
                    {{numberToWords($payment->reunionPeriod->fee)}}
                </span>
    
                <span class="absolute bottom-[55px] whitespace-nowrap font-[Poppins] text-[28px] font-semibold tracking-wide text-black overflow-hidden text-clip block">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</span>
    
                <span class="absolute bottom-[-5px] whitespace-nowrap font-[Poppins] text-[28px] font-semibold tracking-wide text-black uppercase overflow-hidden text-clip block">{{$payment->receipt_number}}</span>
    
            </div>
    
            <div class="relative mt-[330px] ml-[175px] h-[368px] w-[310px] border border-0 border-black">
                <span id="nameText2" class="absolute top-[35px] whitespace-nowrap font-[Poppins] text-[20px] font-semibold tracking-wide text-black uppercase overflow-hidden text-clip block"></span>

                <span class="absolute top-[110px] whitespace-nowrap font-[Poppins] text-[20px] font-semibold tracking-wide text-black uppercase overflow-hidden text-clip block">{{$payment->reunionPeriod->fee}}/-</span>

                <span class="absolute top-[180px] whitespace-nowrap font-[Poppins] text-[20px] font-semibold tracking-wide text-black overflow-hidden text-clip block">{{numberToWords($payment->reunionPeriod->fee)}}</span>

                <span class="absolute top-[258px] whitespace-nowrap font-[Poppins] text-[20px] font-semibold tracking-wide text-black overflow-hidden text-clip block">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M, Y') }}</span>

                <span class="absolute top-[328px] whitespace-nowrap font-[Poppins] text-[20px] font-semibold tracking-wide text-black uppercase overflow-hidden text-clip block">{{$payment->receipt_number}}</span>
            </div>

        </div>
    </div>
    <script>
        formatNameIfOverflow(
            "{{$payment->user->name}}",
            document.getElementById("nameText")
        );
        formatNameIfOverflow(
            "{{$payment->user->name}}",
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
    </script>
</body>
</html>