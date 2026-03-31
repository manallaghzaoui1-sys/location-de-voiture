import React, { useMemo, useState } from 'react';

function CarsCatalog({ cars }) {
    const [search, setSearch] = useState('');
    const [fuel, setFuel] = useState('all');
    const [sort, setSort] = useState('popular');
    const [selectedPriceRanges, setSelectedPriceRanges] = useState([]);

    const fuelOptions = useMemo(() => {
        const set = new Set(cars.map((car) => car.carburant));
        return ['all', ...Array.from(set)];
    }, [cars]);

    const priceRanges = useMemo(
        () => [
            { id: '0-500', label: '0 DH - 500 DH', match: (price) => price <= 500 },
            { id: '500-1000', label: '500 DH - 1000 DH', match: (price) => price > 500 && price <= 1000 },
            { id: '1000+', label: '1000 DH+', match: (price) => price > 1000 },
        ],
        []
    );

    const togglePriceRange = (rangeId) => {
        setSelectedPriceRanges((previous) => (
            previous.includes(rangeId)
                ? previous.filter((item) => item !== rangeId)
                : [...previous, rangeId]
        ));
    };

    const clearAllFilters = () => {
        setSearch('');
        setFuel('all');
        setSort('popular');
        setSelectedPriceRanges([]);
    };

    const filteredCars = useMemo(() => {
        const keyword = search.trim().toLowerCase();
        let output = cars.filter((car) => {
            const fullName = `${car.marque} ${car.modele}`.toLowerCase();
            const matchSearch = keyword === '' || fullName.includes(keyword);
            const matchFuel = fuel === 'all' || car.carburant === fuel;
            const matchPrice = selectedPriceRanges.length === 0
                || priceRanges
                    .filter((range) => selectedPriceRanges.includes(range.id))
                    .some((range) => range.match(car.prix_par_jour));
            return matchSearch && matchFuel && matchPrice;
        });

        if (sort === 'price_asc') {
            output = [...output].sort((a, b) => a.prix_par_jour - b.prix_par_jour);
        } else if (sort === 'price_desc') {
            output = [...output].sort((a, b) => b.prix_par_jour - a.prix_par_jour);
        } else {
            output = [...output].sort((a, b) => (b.sort_key || 0) - (a.sort_key || 0));
        }

        return output;
    }, [cars, fuel, search, selectedPriceRanges, sort, priceRanges]);

    return (
        <div className="catalog-shell">
            <div className="reservation-steps mb-4" data-animate="reveal">
                <div className="reservation-step">
                    <span>1</span>
                    <div>
                        <small>Choix</small>
                        <strong>Selection du vehicule</strong>
                    </div>
                </div>
                <div className="reservation-step">
                    <span>2</span>
                    <div>
                        <small>Retrait / Retour</small>
                        <strong>Aeroport, centre-ville ou agence</strong>
                    </div>
                </div>
                <div className="reservation-step">
                    <span>3</span>
                    <div>
                        <small>Tri rapide</small>
                        <strong>{filteredCars.length} vehicules disponibles</strong>
                    </div>
                </div>
            </div>

            <div className="catalog-layout">
                <aside className="catalog-sidebar" data-animate="reveal">
                    <div className="sidebar-head">
                        <h3>Filtrer</h3>
                        <button type="button" onClick={clearAllFilters}>Effacer</button>
                    </div>

                    <div className="sidebar-block">
                        <h4>Recherche</h4>
                        <input
                            type="text"
                            className="catalog-input"
                            placeholder="Marque ou modele"
                            value={search}
                            onChange={(event) => setSearch(event.target.value)}
                        />
                    </div>

                    <div className="sidebar-block">
                        <h4>Prix range</h4>
                        <div className="checkbox-list">
                            {priceRanges.map((range) => (
                                <label key={range.id}>
                                    <input
                                        type="checkbox"
                                        checked={selectedPriceRanges.includes(range.id)}
                                        onChange={() => togglePriceRange(range.id)}
                                    />
                                    <span>{range.label}</span>
                                </label>
                            ))}
                        </div>
                    </div>

                    <div className="sidebar-block">
                        <h4>Carburant</h4>
                        <select className="catalog-input" value={fuel} onChange={(event) => setFuel(event.target.value)}>
                            {fuelOptions.map((option) => (
                                <option key={option} value={option}>
                                    {option === 'all' ? 'Tous' : option}
                                </option>
                            ))}
                        </select>
                    </div>
                </aside>

                <section className="catalog-content">
                    <div className="catalog-head mb-3" data-animate="reveal">
                        <h2>Selectionnez votre vehicule</h2>
                        <div className="catalog-actions">
                            <label htmlFor="catalog-sort">Sort by</label>
                            <select
                                id="catalog-sort"
                                className="catalog-input"
                                value={sort}
                                onChange={(event) => setSort(event.target.value)}
                            >
                                <option value="popular">Most popular first</option>
                                <option value="price_asc">Prix croissant</option>
                                <option value="price_desc">Prix decroissant</option>
                            </select>
                        </div>
                    </div>

                    {filteredCars.length === 0 && (
                        <div className="alert alert-warning">Aucun vehicule ne correspond a votre filtre.</div>
                    )}

                    <div className="cars-grid">
                        {filteredCars.map((car, index) => (
                            <article
                                className="car-card car-card-pro"
                                key={car.token}
                                data-animate="reveal"
                                style={{ '--motion-delay': `${(index % 6) * 70}ms` }}
                            >
                                <div className="car-media">
                                    <img src={car.image_url} alt={`${car.marque} ${car.modele}`} loading="lazy" />
                                    <span className="car-tag">{car.prix_par_jour < 600 ? 'Economique' : 'Premium'}</span>
                                </div>
                                <div className="car-content">
                                    <h3>{car.marque} {car.modele}</h3>
                                    <p className="car-meta">
                                        <span><i className="fas fa-calendar-alt" /> {car.annee}</span>
                                        <span><i className="fas fa-gas-pump" /> {car.carburant}</span>
                                    </p>
                                    <p className="car-price">
                                        <strong>{Number(car.prix_par_jour).toFixed(2)}</strong> DH <span>/ jour</span>
                                    </p>
                                    <a href={car.details_url} className="btn btn-reserve">
                                        Reserver
                                    </a>
                                </div>
                            </article>
                        ))}
                    </div>
                </section>
            </div>
        </div>
    );
}

export default CarsCatalog;