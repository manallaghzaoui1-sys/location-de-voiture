import React, { useEffect, useMemo, useState } from 'react';

function toDate(value) {
    if (!value) {
        return null;
    }

    const date = new Date(value);
    return Number.isNaN(date.getTime()) ? null : date;
}

function ReservationPriceCalculator({ pricePerDay, dateStartInputId, dateEndInputId, citySelectId }) {
    const [days, setDays] = useState(0);
    const [travelFee, setTravelFee] = useState(0);

    useEffect(() => {
        const dateStartInput = document.getElementById(dateStartInputId);
        const dateEndInput = document.getElementById(dateEndInputId);
        const citySelect = document.getElementById(citySelectId);

        if (!dateStartInput || !dateEndInput || !citySelect) {
            return undefined;
        }

        const recompute = () => {
            const start = toDate(dateStartInput.value);
            const end = toDate(dateEndInput.value);
            const selectedCity = citySelect.options[citySelect.selectedIndex];
            const nextTravelFee = selectedCity?.dataset?.fee ? parseFloat(selectedCity.dataset.fee) : 0;

            if (!start || !end) {
                setDays(0);
                setTravelFee(nextTravelFee || 0);
                return;
            }

            const diff = Math.ceil((end.getTime() - start.getTime()) / (1000 * 60 * 60 * 24));
            setDays(diff > 0 ? diff : 0);
            setTravelFee(nextTravelFee || 0);
        };

        recompute();
        dateStartInput.addEventListener('change', recompute);
        dateEndInput.addEventListener('change', recompute);
        citySelect.addEventListener('change', recompute);

        return () => {
            dateStartInput.removeEventListener('change', recompute);
            dateEndInput.removeEventListener('change', recompute);
            citySelect.removeEventListener('change', recompute);
        };
    }, [citySelectId, dateEndInputId, dateStartInputId]);

    const rentalPrice = useMemo(() => Math.max(days, 0) * pricePerDay, [days, pricePerDay]);
    const totalPrice = useMemo(() => rentalPrice + travelFee, [rentalPrice, travelFee]);

    return (
        <div className="alert alert-info mb-0">
            <div>
                <strong>Prix / jour:</strong> {pricePerDay.toFixed(2)} DH
            </div>
            <div>
                <strong>Nombre jours:</strong> {days}
            </div>
            <div>
                <strong>Prix location:</strong> {rentalPrice.toFixed(2)} DH
            </div>
            <div>
                <strong>Frais ville:</strong> {travelFee.toFixed(2)} DH
            </div>
            <hr />
            <div>
                <strong>Total estimé:</strong> {totalPrice.toFixed(2)} DH
            </div>
        </div>
    );
}

export default ReservationPriceCalculator;

