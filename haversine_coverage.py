import math


#  radius of earth in km
R = 6371
# in km
MAX_DISTANCE = 10

locations = [
    {'id': 1000, 'zip_code': '37069', 'lat': 45.35, 'lng': 10.84},
    {'id': 1001, 'zip_code': '37121', 'lat': 45.44, 'lng': 10.99},
    {'id': 1001, 'zip_code': '37129', 'lat': 45.44, 'lng': 11.00},
    {'id': 1001, 'zip_code': '37133', 'lat': 45.43, 'lng': 11.02},
]

shoppers = [
    {'id': 'S1', 'lat': 45.46, 'lng': 11.03, 'enabled': True},
    {'id': 'S2', 'lat': 45.46, 'lng': 10.12, 'enabled': True},
    {'id': 'S3', 'lat': 45.34, 'lng': 10.81, 'enabled': True},
    {'id': 'S4', 'lat': 45.76, 'lng': 10.57, 'enabled': True},
    {'id': 'S5', 'lat': 45.34, 'lng': 10.63, 'enabled': True},
    {'id': 'S6', 'lat': 45.42, 'lng': 10.81, 'enabled': True},
    {'id': 'S7', 'lat': 45.34, 'lng': 10.94, 'enabled': False},
]


def haversine(lat1, lng1, lat2, lng2):
    lat1, lat2, lng1, lng2 = math.radians(lat1),  math.radians(lat2), math.radians(lng1), math.radians(lng2)
    dlng = lng2 - lng1
    dlat = lat2 - lat1
    a = math.sin(dlat/2)**2 + math.cos(lat1) * math.cos(lat2) * math.sin(dlng/2)**2
    c = 2 * math.asin(math.sqrt(a))
    return R * c


def main():
    location_counts = len(locations)
    enabled_shoppers = list(filter(lambda x: x.get('enabled'), shoppers))
    result = []
    for shopper in enabled_shoppers:
        locations_covered = list(filter(
            lambda loc: haversine(shopper['lat'], shopper['lng'], loc['lat'], loc['lng']) <= MAX_DISTANCE, locations))
        if locations_covered:
            result.append({
                'shopper_id': shopper['id'],
                'coverage': len(locations_covered) * 100 / len(enabled_shoppers)
            })

    result.sort(key=lambda shopper: shopper['coverage'], reverse=True)
    return result


if __name__ == '__main__':
    print(main())